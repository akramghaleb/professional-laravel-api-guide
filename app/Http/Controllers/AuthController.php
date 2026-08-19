<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponses;

    /**
     * Authenticate the user.
     */
    public function login(): JsonResponse
    {
        return $this->ok('Hello, Login!');
    }
}
