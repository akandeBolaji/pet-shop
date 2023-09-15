<?php

namespace PetShop\CurrencyExchanger\Contracts;

use Illuminate\Http\JsonResponse;

interface ResponseHandlerContract
{
    /**
     * Prepare a JSON response.
     *
     * @param int $status_code The HTTP status code.
     * @param mixed $data The main data to include in the response.
     * @param mixed $error A general error message or data.
     * @param array<string, mixed> $errors Specific error messages or data.
     * @param array<string, mixed> $trace A trace or debug information.
     *
     * @return JsonResponse The formatted JSON response.
     */
    public function jsonResponse(
        int $status_code,
        mixed $data = [],
        mixed $error = null,
        array $errors = [],
        array $trace = []
    ): JsonResponse;
}
