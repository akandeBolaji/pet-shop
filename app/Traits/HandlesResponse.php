<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait HandlesResponse
{
    /**
     * Prepare json response.
     *
     * @param array $errors
     * @param array $trace
     */
    protected function jsonResponse(
        int $status_code = Response::HTTP_OK,
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
