<?php

namespace Webkul\ApiResources\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Illuminate\Support\Facades\Auth;
use Webkul\ApiResources\Models\Auth\Authentication;

final class AuthProcessor implements ProcessorInterface
{
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (! $data instanceof Authentication) {
            throw new \InvalidArgumentException('Expected Authentication object');
        }

        $email = $data->email;
        $password = $data->password;
        $deviceName = $data->deviceName ?? 'api';

        if (! $email || ! $password) {
            throw new \Exception('Email and password are required', 400);
        }

        if (! Auth::guard('admin')->attempt([
            'email'    => $email,
            'password' => $password,
        ])) {
            throw new \Exception('Invalid credentials', 401);
        }

        $admin = Auth::guard('admin')->user();
        $token = $admin->createToken($deviceName)->plainTextToken;

        $data->token = $token;
        $data->message = 'Logged in successfully';
        $data->user = new \Webkul\ApiResources\Models\Auth\User(
            id: $admin->id,
            name: $admin->name,
            email: $admin->email,
        );

        return $data;
    }
}
