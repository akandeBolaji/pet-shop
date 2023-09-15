<?php

namespace PetShop\CurrencyExchanger\Http\Requests;

class CurrencyConversionRequest extends APIFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0',
            'currency_to_exchange' => 'required|string|max:3',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
