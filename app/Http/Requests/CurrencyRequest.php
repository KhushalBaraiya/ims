<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
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
        $currency = $this->route('currency');
        $currencyId = $currency instanceof \App\Models\Currency ? $currency->id : $currency;

        return [
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:10',
                'unique:currencies,code,' . $currencyId,
            ],
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'is_default' => 'nullable|boolean',
        ];
    }

    /**
     * Custom validation attribute names.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Currency Name',
            'code' => 'Currency Code',
            'symbol' => 'Currency Symbol',
            'exchange_rate' => 'Exchange Rate',
            'status' => 'Status',
            'is_default' => 'Is Default',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'code.unique' => 'This Currency Code has already been taken.',
        ];
    }
}
