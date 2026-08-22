<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

trait ApiResponses
{
    /**
     * Return a successful response.
     */
    protected function ok(string $message, mixed $data = null): JsonResponse
    {
        return $this->success($message, $data);
    }

    /**
     * Return a success response with the given payload.
     */
    protected function success(string $message, mixed $data = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Return a resource or paginated resource collection in the standard envelope.
     */
    protected function resource(
        string $message,
        JsonResource $resource,
        int $statusCode = 200,
    ): JsonResponse {
        $payload = $resource->response()->getData(true);
        $data = array_key_exists('meta', $payload)
            ? [
                'items' => $payload['data'],
                'links' => $payload['links'],
                'meta' => $payload['meta'],
            ]
            : $payload['data'];

        return $this->success($message, $data, $statusCode);
    }

    /**
     * Return an error response.
     */
    protected function error(string $message, mixed $data = null, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Return a 403 response for a forbidden request.
     */
    protected function notAuthorized(string $message): JsonResponse
    {
        return $this->error($message, null, 403);
    }
}
