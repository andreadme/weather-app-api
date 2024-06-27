<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * Set success response
     */
    protected function success(?string $message, mixed $data, $statusCode = 201): JsonResponse
    {
        $payload = [
            'result' => [
                'success' => true,
                'message' => $message,
                'data' => $data,
            ],
        ];

        return response()->json($payload, $statusCode);
    }

    /**
     * Set Error response
     */
    protected function error($message = 'Internal error', $statusCode = 500): JsonResponse
    {
        // reorganize array message
        if (! is_scalar($message)) {
            $message = array_map('current', $message);
        }

        return response()->json([
            'result' => [
                'success' => false,
                'message' => $message,
                'data' => null,
            ],
        ], $statusCode);
    }
}
