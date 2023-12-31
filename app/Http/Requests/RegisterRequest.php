<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;

class RegisterRequest extends APIFormRequest
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
     * @return array<string, array<int, Password|string|null>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|string|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'avatar' => 'nullable|uuid|exists:files,uuid',
            'address' => 'required|string',
            'is_marketing' => 'nullable|string',
        ];
    }
}
