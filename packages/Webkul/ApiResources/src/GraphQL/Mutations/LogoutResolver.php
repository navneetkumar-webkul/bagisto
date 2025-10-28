<?php

namespace Webkul\ApiResources\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;

class LogoutResolver
{
    public function __invoke($item, array $context): ?object
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            throw new \Exception('Unauthenticated', 401);
        }

        // Revoke current token
        if ($user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return (object)[
            'message' => 'Logged out successfully',
            'success' => true,
        ];
    }
}
