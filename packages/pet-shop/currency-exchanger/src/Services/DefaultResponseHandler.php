<?php

namespace PetShop\CurrencyExchanger\Services;

use Illuminate\Http\JsonResponse;
use PetShop\CurrencyExchanger\Contracts\ResponseHandlerContract;

class DefaultResponseHandler implements ResponseHandlerContract
{
    public function jsonResponse(
        int $status_code,
        mixed $data = [],
        mixed $error = null,
        array $errors = [],
        array $trace = []
    ): JsonResponse {
        return response()->json([
            'success' => $status_code >= 200 && $status_code <= 299 ? 1 : 0,
            'data' => $data,
            'error' => $error,
            'errors' => $errors,
            'trace' => $trace,
        ], $status_code);
    }
}
