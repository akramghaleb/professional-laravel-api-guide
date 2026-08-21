<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    /**
     * Return a successful response.
     */
    protected function ok(string $message, array $data = []): JsonResponse
    {
        return $this->success($message, $data, 200);
    }

    /**
     * Return a success response with the given payload.
     */
    protected function success(string $message, array $data = [], int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }

    /**
     * Return an error response.
     */
    protected function error(string $message, int $statusCode): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }
}
