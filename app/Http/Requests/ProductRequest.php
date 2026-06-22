<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $product = $this->route('product');
        $productId = $product instanceof \App\Models\Product ? $product->id : $product;

        $rules = [
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                'unique:products,code,' . $productId,
            ],
            'barcode' => [
                'nullable',
                'string',
                'max:50',
                'unique:products,barcode,' . $productId,
            ],
            'brand_id' => 'required|exists:brands,id',
            'main_category_id' => 'required|exists:main_categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'unit_name' => 'required|string|max:255',
            'unit_code' => 'required|string|max:50',
            'base_unit' => 'nullable|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'opening_stock' => 'nullable|numeric|min:0',
            'minimum_stock_alert' => 'nullable|numeric|min:0',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'is_featured' => 'nullable|boolean',
            
            // Optional specs
            'manufacturer' => 'nullable|string|max:255',
            'model_number' => 'nullable|string|max:255',
            'part_number' => 'nullable|string|max:255',
            'warranty' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:100',
            'weight' => 'nullable|string|max:100',
            'country_of_origin' => 'nullable|string|max:100',
        ];

        // Image validation (only require on create if desired, but we can make it nullable on both since it could be left empty or have placeholder)
        if ($this->isMethod('POST')) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
        }

        $rules['gallery'] = 'nullable|array';
        $rules['gallery.*'] = 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';

        return $rules;
    }

    /**
     * Custom validation attribute names.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Product Name',
            'code' => 'Product Code (SKU)',
            'barcode' => 'Barcode',
            'brand_id' => 'Brand',
            'main_category_id' => 'Main Category',
            'sub_category_id' => 'Sub Category',
            'unit_name' => 'Unit Name',
            'unit_code' => 'Unit Code',
            'base_unit' => 'Base Unit',
            'supplier_id' => 'Supplier',
            'purchase_price' => 'Purchase Price',
            'selling_price' => 'Selling Price',
            'mrp' => 'MRP',
            'tax_percentage' => 'Tax Percentage',
            'discount_percentage' => 'Discount Percentage',
            'opening_stock' => 'Opening Stock',
            'minimum_stock_alert' => 'Minimum Stock Alert',
            'image' => 'Product Image',
            'gallery' => 'Product Gallery',
            'gallery.*' => 'Gallery Image',
            'status' => 'Status',
            'is_featured' => 'Featured Product',
            'manufacturer' => 'Manufacturer',
            'model_number' => 'Model Number',
            'part_number' => 'Part Number',
            'warranty' => 'Warranty',
            'color' => 'Color',
            'weight' => 'Weight',
            'country_of_origin' => 'Country of Origin',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'The Product Code (SKU) field is required.',
            'code.unique' => 'This SKU has already been taken.',
            'code.alpha_dash' => 'The SKU must only contain letters, numbers, dashes, and underscores.',
            'barcode.unique' => 'This Barcode has already been taken.',
        ];
    }
}
