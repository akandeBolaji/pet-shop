<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class OrderStatusRequest extends APIFormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        if (isset($this->order_status)) {
            return [
                'title' => [
                    'required',
                    'string',
                    Rule::unique('order_statuses')->ignore($this->order_status),
                ],
            ];
        }

        return [
            'title' => 'required|string|unique:order_statuses',
        ];
    }
}
