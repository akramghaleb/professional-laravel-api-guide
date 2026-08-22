<?php

namespace Tests\Unit;

use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class ApiResponsesTest extends TestCase
{
    public function test_success_response_uses_the_standard_envelope(): void
    {
        $response = $this->responder()->successResponse(
            'Operation completed.',
            ['id' => 10],
            201,
        );

        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame([
            'message' => 'Operation completed.',
            'status' => 201,
            'data' => ['id' => 10],
        ], $response->getData(true));
    }

    public function test_error_response_uses_the_same_standard_envelope(): void
    {
        $response = $this->responder()->errorResponse(
            'The request is invalid.',
            ['field' => 'email'],
            422,
        );

        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame([
            'message' => 'The request is invalid.',
            'status' => 422,
            'data' => ['field' => 'email'],
        ], $response->getData(true));
    }

    private function responder(): object
    {
        return new class
        {
            use ApiResponses;

            public function successResponse(string $message, mixed $data, int $status): JsonResponse
            {
                return $this->success($message, $data, $status);
            }

            public function errorResponse(string $message, mixed $data, int $status): JsonResponse
            {
                return $this->error($message, $data, $status);
            }
        };
    }
}
