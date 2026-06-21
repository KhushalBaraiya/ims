<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
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
        $unit = $this->route('unit');
        $unitId = $unit instanceof \App\Models\Unit ? $unit->id : $unit;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:units,name,' . $unitId,
            ],
            'short_name' => [
                'required',
                'string',
                'max:50',
                'unique:units,short_name,' . $unitId,
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
            'name' => 'Unit Name',
            'short_name' => 'Short Name',
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
            'name.unique' => 'This Unit Name has already been taken.',
            'short_name.unique' => 'This Short Name has already been taken.',
        ];
    }
}
