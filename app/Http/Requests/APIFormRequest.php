<?php

namespace App\Http\Requests;

use App\Traits\HandlesResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class APIFormRequest extends FormRequest
{
    use HandlesResponse;

    /**
     * Determine if the user is authorized to make this request.
     */
    abstract public function authorize(): bool;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    abstract public function rules(): array;

    /**
     * Customize the response when validation fails.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            $this->jsonResponse(
                status_code: Response::HTTP_UNPROCESSABLE_ENTITY,
                error: __('validation.invalid_inputs'),
                errors: $validator->errors()->toArray()
            )
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function validFields(): array
    {
        return (array) $this->validated();
    }
}
