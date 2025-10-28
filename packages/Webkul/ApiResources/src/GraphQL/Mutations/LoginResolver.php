<?php

namespace Webkul\ApiResources\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;

class LoginResolver
{
    public function __invoke($item, array $context): ?object
    {
        $input = $context['args']['input'] ?? [];
        $email = $input['email'] ?? null;
        $password = $input['password'] ?? null;
        $deviceName = $input['deviceName'] ?? 'api';

        if (!$email || !$password) {
            throw new \Exception('Email and password are required', 400);
        }

        if (!Auth::guard('admin')->attempt([
            'email' => $email,
            'password' => $password,
        ])) {
            throw new \Exception('Invalid credentials', 401);
        }

        $user = Auth::guard('admin')->user();
        $token = $user->createToken($deviceName)->plainTextToken;

        return (object)[
            'message' => 'Logged in successfully',
            'token' => $token,
            'user' => (object)[
                'id' => (string) $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ];
    }
}
