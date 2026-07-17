<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $brand = $this->route('brand');
        $brandId = $brand instanceof \App\Models\Brand ? $brand->id : $brand;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:brands,name,' . $brandId,
            ],
            'slug' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                'unique:brands,slug,' . $brandId,
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'status' => [
                'required',
                'in:active,inactive',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
            ],
        ];
    }

    /**
     * Custom validation attribute names.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Brand Name',
            'slug' => 'Brand Code',
            'description' => 'Description',
            'status' => 'Status',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'slug.required' => 'The Brand Code field is required.',
            'slug.unique' => 'This Brand Code has already been taken.',
            'slug.alpha_dash' => 'The Brand Code must only contain letters, numbers, dashes, and underscores.',
        ];
    }
}
