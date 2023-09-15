<?php

namespace PetShop\CurrencyExchanger\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use PetShop\CurrencyExchanger\Contracts\ResponseHandlerContract;

abstract class APIFormRequest extends FormRequest
{    
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
        $responseHandler = app(ResponseHandlerContract::class);
        throw new HttpResponseException(
            $responseHandler->jsonResponse(
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
