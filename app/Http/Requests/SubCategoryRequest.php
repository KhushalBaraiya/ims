<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubCategoryRequest extends FormRequest
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
        $subCategory = $this->route('sub_category');
        $subCategoryId = $subCategory instanceof \App\Models\SubCategory ? $subCategory->id : $subCategory;

        return [
            'main_category_id' => 'required|exists:main_categories,id',
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                'unique:sub_categories,slug,' . $subCategoryId,
            ],
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * Custom validation attribute names.
     */
    public function attributes(): array
    {
        return [
            'main_category_id' => 'Main Category',
            'name' => 'Sub Category Name',
            'slug' => 'Sub Category Code',
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
            'slug.required' => 'The Sub Category Code field is required.',
            'slug.unique' => 'This Sub Category Code has already been taken.',
            'slug.alpha_dash' => 'The Sub Category Code must only contain letters, numbers, dashes, and underscores.',
        ];
    }
}
