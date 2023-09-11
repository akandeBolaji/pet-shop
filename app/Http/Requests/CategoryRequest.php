<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CategoryRequest extends APIFormRequest
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
        if (isset($this->category)) {
            return [
                'title' => [
                    'required',
                    'string',
                    Rule::unique('categories')->ignore($this->category),
                ],
            ];
        }

        return [
            'title' => 'required|string|unique:categories',
        ];
    }
}
