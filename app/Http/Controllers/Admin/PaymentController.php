<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('user', 'order')->latest()->get();
        return view('admin.payment.index', compact('payments'));
    }

    public function create()
    {
        $users  = User::all();
        $orders = Order::all();
        return view('admin.payment.create', compact('users', 'orders'));
    }

    public function show($id)
    {
        $payment = Payment::with('user','order')->findOrFail($id);
        return view('admin.payment.show', compact('payment'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'user_id'        => 'required',
            'order_id'       => 'required',
            'amount'         => 'required|numeric|min:0',
            'currency'       => 'required|string|max:10',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
            'gateway'        => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'paid_at'        => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        Payment::create($request->all());

        return redirect()->route('admin.payment.index')
            ->with('success', 'Payment Added Successfully');
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        $users   = User::all();
        $orders  = Order::all();
        return view('admin.payment.create', compact('payment', 'users', 'orders'));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'user_id'        => 'required',
            'order_id'       => 'required',
            'amount'         => 'required|numeric|min:0',
            'currency'       => 'required|string|max:10',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
            'gateway'        => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'paid_at'        => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        $payment->update($request->all());

        return redirect()->route('admin.payment.index')
            ->with('success', 'Payment Updated Successfully');
    }

    public function destroy($id)
    {
        Payment::findOrFail($id)->delete();
        return redirect()->route('admin.payment.index')
            ->with('success', 'Payment Deleted Successfully');
    }
}
