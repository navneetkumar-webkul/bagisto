<?php

namespace Webkul\ApiResources\GraphQL\Mutations;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Auth;
use Webkul\User\Models\Admin;

class LoginMutation extends Mutation
{
    protected $attributes = [
        'name' => 'Login',
        'description' => 'Login with email and password',
    ];

    public function type(): Type
    {
        return $this->builder()->objectType([
            'token' => ['type' => Type::string()],
            'message' => ['type' => Type::string()],
            'user' => ['type' => $this->builder()->objectType([
                'id' => ['type' => Type::int()],
                'name' => ['type' => Type::string()],
                'email' => ['type' => Type::string()],
            ])],
        ]);
    }

    public function args(): array
    {
        return [
            'email' => [
                'name' => 'email',
                'type' => Type::nonNull(Type::string()),
            ],
            'password' => [
                'name' => 'password',
                'type' => Type::nonNull(Type::string()),
            ],
            'device_name' => [
                'name' => 'device_name',
                'type' => Type::nonNull(Type::string()),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        if (!Auth::guard('admin')->attempt([
            'email' => $args['email'],
            'password' => $args['password'],
        ])) {
            throw new \Exception('Invalid credentials', 401);
        }

        $user = Auth::guard('admin')->user();
        $token = $user->createToken($args['device_name'])->plainTextToken;

        return [
            'message' => 'Logged in successfully',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ];
    }
}
