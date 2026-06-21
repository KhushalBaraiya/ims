<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseReturnRequest extends FormRequest
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
            'purchase_id' => 'required|exists:purchases,id',
            'return_date' => 'required|date',
            'reference_no' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'refunded_amount' => 'required|numeric|min:0',
            'status' => 'required|in:Completed,Pending',
            
            // Return items
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.reason' => 'nullable|string|max:255',
        ];
    }

    /**
     * Custom validation attribute names.
     */
    public function attributes(): array
    {
        return [
            'purchase_id' => 'Purchase Invoice',
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
