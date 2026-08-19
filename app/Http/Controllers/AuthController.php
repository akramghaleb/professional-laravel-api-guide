<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiLoginRequest;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponses;

    /**
     * Authenticate the user.
     */
    public function login(ApiLoginRequest $request): JsonResponse
    {
        return $this->ok($request->get('email'));
    }

    /**
     * Register a new user.
     */
    public function register(): JsonResponse
    {
        return $this->ok('register');
    }
}
