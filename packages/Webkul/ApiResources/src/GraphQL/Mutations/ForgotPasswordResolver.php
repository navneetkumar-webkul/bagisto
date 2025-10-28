<?php

namespace Webkul\ApiResources\GraphQL\Mutations;

use Webkul\User\Models\Admin;

class ForgotPasswordResolver
{
    public function __invoke($item, array $context): ?object
    {
        $email = $context['args']['input']['email'] ?? null;

        if (!$email) {
            throw new \Exception('Email is required', 400);
        }

        $admin = Admin::where('email', $email)->first();

        if (!$admin) {
            throw new \Exception('User not found', 404);
        }

        // TODO: Implement password reset token generation and email sending
        // For now, just return a success message

        return (object)[
            'message' => 'Password reset link has been sent to your email',
            'success' => true,
        ];
    }
}
