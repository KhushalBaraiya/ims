<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $unit   = $this->route('unit');
        $unitId = $unit instanceof \App\Models\Unit ? $unit->id : $unit;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:units,name,' . $unitId,
            ],
            'short_name' => [
                'required',
                'string',
                'max:20',
            ],
            'status' => [
                'required',
                'in:active,inactive',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'       => 'Unit Name',
            'short_name' => 'Short Name',
            'status'     => 'Status',
        ];
    }
}
