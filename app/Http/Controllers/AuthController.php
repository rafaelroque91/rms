<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponses;

    public function __construct(
        private readonly AuthService $authService)
    {
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request) : JsonResponse
    {
        $user = $this->authService->register($request->all());

        return $this->created('User registered successfully', $user);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request) : JsonResponse
    {
        try {
            $token = $this->authService->login($request->all());
            return $this->success('Login successfull', ['access_token' => $token, 'token_type' => 'Bearer']);
        } catch (ValidationException $e) {
            return $this->error($e->getMessage(), 401);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());
        return $this->success('Logged out successfully', 200);
    }
}
