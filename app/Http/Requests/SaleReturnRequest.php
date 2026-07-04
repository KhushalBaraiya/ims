<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleReturnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'sale_id' => 'required|exists:sales,id',
            'return_date' => 'required|date',
            'reference_no' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'refunded_amount' => 'required|numeric|min:0',
            'status' => 'required|in:Completed,Pending',
            
            // Return items
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:0',
            'items.*.reason' => 'nullable|string|max:255',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $saleId = $this->input('sale_id');
            if (!$saleId) return;

            $sale = \App\Models\Sale::find($saleId);
            if (!$sale) return;

            // Compute refundable amount
            $refundableTotal = 0;
            if ($this->has('items') && is_array($this->input('items'))) {
                foreach ($this->input('items') as $item) {
                    $qty = (int) ($item['quantity'] ?? 0);
                    if ($qty <= 0) continue;

                    $saleItem = $sale->items()->where('product_id', $item['product_id'])->first();
                    if ($saleItem) {
                        $origQty = max(1.0, (float)$saleItem->quantity);
                        $unitDisc = (float)($saleItem->discount_amount / $origQty);
                        $unitTax = (float)($saleItem->tax_amount / $origQty);

                        $rowDisc = round($unitDisc * $qty, 2);
                        $rowTax = round($unitTax * $qty, 2);
                        $rowTotal = ($qty * $saleItem->unit_price) + $rowTax - $rowDisc;

                        $refundableTotal += $rowTotal;
                    }
                }
            }

            $refundedAmount = (float) $this->input('refunded_amount', 0);
            if ($refundedAmount > round($refundableTotal, 2)) {
                $validator->errors()->add('refunded_amount', 'Refunded amount cannot exceed the calculated refundable amount of ' . number_format($refundableTotal, 2) . '.');
            }
        });
    }

    /**
     * Custom validation attribute names.
     */
    public function attributes(): array
    {
        return [
            'sale_id' => 'Sale Invoice',
            'return_date' => 'Return Date',
            'refunded_amount' => 'Refunded Amount',
            'status' => 'Status',
            'items' => 'Returned Items',
            'items.*.product_id' => 'Product ID',
            'items.*.quantity' => 'Return Quantity',
            'items.*.reason' => 'Return Reason',
        ];
    }

    /**
     * Custom messages for validation.
     */
    public function messages(): array
    {
        return [
            'items.required' => 'At least one item must be returned.',
            'items.min' => 'At least one item must be returned.',
            'items.*.quantity.min' => 'Return quantity cannot be negative.',
        ];
    }
}
