<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    /**
     * Return a successful response.
     */
    protected function ok(string $message): JsonResponse
    {
        return $this->success($message, 200);
    }

    /**
     * Return a success response whose payload status matches the HTTP status.
     */
    protected function success(string $message, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }
}
