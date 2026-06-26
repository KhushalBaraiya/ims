<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
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
            'customer_id' => 'required|exists:customers,id',
            'sales_person_id' => 'required|exists:users,id',
            'invoice_date' => 'required|date',
            'reference_no' => 'nullable|string|max:255',
            'payment_method' => 'required|string|max:100',
            'paid_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:Draft,Completed,Cancelled',
            
            // Item details validation
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.tax_amount' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Custom validation attribute names.
     */
    public function attributes(): array
    {
        return [
            'customer_id' => 'Customer',
            'sales_person_id' => 'Sales Person',
            'invoice_date' => 'Invoice Date',
            'payment_method' => 'Payment Method',
            'paid_amount' => 'Paid Amount',
            'discount_amount' => 'Discount',
            'tax_amount' => 'Tax',
            'shipping_amount' => 'Shipping Charge',
            'status' => 'Status',
            'items' => 'Sale Items',
            'items.*.product_id' => 'Product ID',
            'items.*.quantity' => 'Quantity',
            'items.*.unit_price' => 'Unit Price',
            'items.*.discount_amount' => 'Item Discount',
            'items.*.tax_amount' => 'Item Tax',
        ];
    }

    /**
     * Custom messages for validation.
     */
    public function messages(): array
    {
        return [
            'items.required' => 'You must add at least one product to the invoice.',
            'items.min' => 'You must add at least one product to the invoice.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
