<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'      => 'required|exists:customers,id',
            'sales_person_id'  => 'nullable|exists:users,id',
            'invoice_date'     => 'required|date',
            'payment_method'   => 'required|string|max:100',
            'paid_amount'      => 'required|numeric|min:0',
            'discount_type'    => 'nullable|in:fixed,percentage',
            'discount_value'   => 'nullable|numeric|min:0',
            'tax_percentage'   => 'nullable|numeric|min:0|max:100',
            'shipping_amount'  => 'nullable|numeric|min:0',
            'notes'            => 'nullable|string',
            'status'           => 'required|in:Completed,Pending,Draft,Repair,Ordered',

            'items'                       => 'required|array|min:1',
            'items.*.product_id'          => 'required|exists:products,id',
            'items.*.quantity'            => 'required|integer|min:1',
            'items.*.unit_price'          => 'required|numeric|min:0',
            'items.*.discount_amount'     => 'nullable|numeric|min:0',
            'items.*.tax_amount'          => 'nullable|numeric|min:0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $discountType = $this->input('discount_type');
            $discountValue = (float) $this->input('discount_value', 0);
            
            // Calculate subtotal
            $subTotal = 0;
            if ($this->has('items') && is_array($this->input('items'))) {
                foreach ($this->input('items') as $item) {
                    $subTotal += ((float) ($item['quantity'] ?? 0) * (float) ($item['unit_price'] ?? 0));
                }
            }

            if ($discountType === 'percentage') {
                if ($discountValue > 100) {
                    $validator->errors()->add('discount_value', 'Discount percentage cannot exceed 100%.');
                }
            } elseif ($discountType === 'fixed') {
                if ($discountValue > $subTotal) {
                    $validator->errors()->add('discount_value', 'Discount amount cannot exceed the subtotal.');
                }
            }
        });
    }

    public function attributes(): array
    {
        return [
            'customer_id'      => 'Customer',
            'sales_person_id'  => 'Sales Person',
            'invoice_date'     => 'Invoice Date',
            'payment_method'   => 'Payment Method',
            'paid_amount'      => 'Paid Amount',
            'discount_type'    => 'Discount Type',
            'discount_value'   => 'Discount Value',
            'tax_percentage'   => 'Tax Percentage',
            'shipping_amount'  => 'Shipping Charge',
            'status'           => 'Status',
            'items'            => 'Sale Items',
            'items.*.product_id'      => 'Product ID',
            'items.*.quantity'        => 'Quantity',
            'items.*.unit_price'      => 'Unit Price',
            'items.*.discount_amount' => 'Item Discount',
            'items.*.tax_amount'      => 'Item Tax',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'You must add at least one product to the invoice.',
            'items.min'      => 'You must add at least one product to the invoice.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
