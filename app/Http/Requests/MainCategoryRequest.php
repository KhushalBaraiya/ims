<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MainCategoryRequest extends FormRequest
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
        $category = $this->route('main_category');
        $categoryId = $category instanceof \App\Models\MainCategory ? $category->id : $category;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:main_categories,name,' . $categoryId,
            ],
            'slug' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                'unique:main_categories,slug,' . $categoryId,
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'status' => [
                'required',
                'in:active,inactive',
            ],
        ];
    }

    /**
     * Custom validation attribute names.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Category Name',
            'slug' => 'Category Code',
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
            'slug.required' => 'The Category Code field is required.',
            'slug.unique'   => 'This Category Code has already been taken.',
            'slug.regex'    => 'The Category Code must be uppercase letters, numbers, and underscores only.',
        ];
    }
}
