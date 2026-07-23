<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayController extends Controller
{
    /**
     * Create a Razorpay Order for a pending purchase payment.
     * Called via AJAX before showing the Razorpay checkout modal.
     */
    public function createOrder(Request $request): JsonResponse
    {
        Gate::authorize('purchases.create');

        $request->validate([
            'amount' => 'required|numeric|min:1',   // in rupees (or active currency)
        ]);

        $amountPaise = (int) round((float) $request->amount * 100); // Razorpay works in paise

        $api = new Api(
            config('services.razorpay.key_id'),
            config('services.razorpay.key_secret')
        );

        $order = $api->order->create([
            'receipt'         => 'PUR-' . uniqid(),
            'amount'          => $amountPaise,
            'currency'        => 'INR',
            'payment_capture' => 1,
        ]);

        return response()->json([
            'order_id'  => $order['id'],
            'amount'    => $amountPaise,
            'currency'  => 'INR',
            'key_id'    => config('services.razorpay.key_id'),
        ]);
    }

    /**
     * Verify payment signature and save purchase.
     * Replaces the normal purchases.store when payment_method = Razorpay.
     */
    public function verifyAndStore(PurchaseRequest $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('purchases.create');

        // ── 1. Verify Razorpay signature ──────────────────────────────────
        $razorpayOrderId   = $request->input('razorpay_order_id');
        $razorpayPaymentId = $request->input('razorpay_payment_id');
        $razorpaySignature = $request->input('razorpay_signature');

        if (!$razorpayOrderId || !$razorpayPaymentId || !$razorpaySignature) {
            return back()->withInput()->withErrors([
                'razorpay' => 'Razorpay payment details are missing. Please try again.',
            ]);
        }

        try {
            $api = new Api(
                config('services.razorpay.key_id'),
                config('services.razorpay.key_secret')
            );
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature'  => $razorpaySignature,
            ]);
        } catch (SignatureVerificationError $e) {
            return back()->withInput()->withErrors([
                'razorpay' => 'Payment verification failed. Signature mismatch.',
            ]);
        }

        // ── 2. Save purchase (same as PurchaseController@store) ───────────
        DB::beginTransaction();
        try {
            $purchaseNo = $request->input('purchase_no') ?: ('PUR-' . date('Ymd') . '-' . uniqid());

            $subTotal = 0;
            foreach ($request->items as $item) {
                $subTotal += ((float) $item['quantity'] * (float) $item['purchase_price']);
            }

            $taxAmount      = (float) ($request->tax_amount      ?? 0);
            $discountAmount = (float) ($request->discount_amount ?? 0);
            $shippingAmount = (float) ($request->shipping_amount ?? 0);
            $grandTotal     = $subTotal + $taxAmount + $shippingAmount - $discountAmount;
            $paidAmount     = $grandTotal;   // fully paid via Razorpay
            $dueAmount      = 0.00;

            $purchase = Purchase::create([
                'purchase_no'     => $purchaseNo,
                'purchase_date'   => $request->purchase_date,
                'supplier_id'     => $request->supplier_id,
                'reference_no'    => $razorpayPaymentId, // store payment ID as reference
                'sub_total'       => $subTotal,
                'tax_amount'      => $taxAmount,
                'discount_amount' => $discountAmount,
                'shipping_amount' => $shippingAmount,
                'grand_total'     => $grandTotal,
                'paid_amount'     => $paidAmount,
                'due_amount'      => $dueAmount,
                'payment_method'  => 'Razorpay',
                'notes'           => $request->notes,
                'status'          => $request->status,
                'user_id'         => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $qty       = (float) $item['quantity'];
                $price     = (float) $item['purchase_price'];
                $itemDisc  = (float) ($item['discount_amount'] ?? 0);
                $itemTax   = (float) ($item['tax_amount']      ?? 0);
                $itemTotal = ($qty * $price) + $itemTax - $itemDisc;

                $purchase->items()->create([
                    'product_id'      => $item['product_id'],
                    'quantity'        => $qty,
                    'purchase_price'  => $price,
                    'discount_amount' => $itemDisc,
                    'tax_amount'      => $itemTax,
                    'total_amount'    => $itemTotal,
                ]);

                if ($request->status === 'received') {
                    $product = Product::with('stock')->findOrFail($item['product_id']);
                    $stock   = $product->stock ?? $product->stock()->create(['quantity' => 0]);
                    $stock->increment('quantity', $qty);
                }
            }

            DB::commit();

            ActivityLog::log(
                'Purchase Created (Razorpay)',
                "Created purchase {$purchase->purchase_no} via Razorpay payment {$razorpayPaymentId}."
            );

            return redirect()->route('purchases.index')
                ->with('success', "Purchase saved & payment of ₹{$grandTotal} received via Razorpay (ID: {$razorpayPaymentId}).");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }
    }
}
