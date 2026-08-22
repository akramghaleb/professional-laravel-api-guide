<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiResponseContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_returns_the_standard_envelope_and_a_token(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertOk()
            ->assertJsonPath('message', 'Authenticated successfully.')
            ->assertJsonPath('status', 200)
            ->assertJsonStructure(['message', 'status', 'data' => ['token']]);
    }

    public function test_validation_errors_use_the_standard_envelope(): void
    {
        $this->postJson('/api/login', [])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Validation failed.')
            ->assertJsonPath('status', 422)
            ->assertJsonStructure(['message', 'status', 'data' => ['errors']]);
    }

    public function test_authenticated_user_response_does_not_expose_model_fields_at_the_root(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/user')
            ->assertOk()
            ->assertJsonPath('status', 200)
            ->assertJsonMissingPath('email')
            ->assertJsonPath('data.attributes.email', $user->email);
    }

    public function test_paginated_order_response_keeps_items_links_and_meta_inside_data(): void
    {
        $user = User::factory()->create();
        Order::factory()->count(2)->create(['user_id' => $user->id]);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/orders')
            ->assertOk()
            ->assertJsonPath('message', 'Orders retrieved successfully.')
            ->assertJsonPath('status', 200)
            ->assertJsonCount(2, 'data.items')
            ->assertJsonStructure([
                'message',
                'status',
                'data' => ['items', 'links', 'meta'],
            ]);
    }

    public function test_unauthenticated_response_uses_the_standard_envelope(): void
    {
        $this->getJson('/api/v1/orders')
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'Unauthenticated.',
                'status' => 401,
                'data' => null,
            ]);
    }

    public function test_unknown_api_route_uses_the_standard_envelope(): void
    {
        $this->getJson('/api/not-a-real-route')
            ->assertNotFound()
            ->assertJsonPath('status', 404)
            ->assertJsonStructure(['message', 'status', 'data']);
    }
}
