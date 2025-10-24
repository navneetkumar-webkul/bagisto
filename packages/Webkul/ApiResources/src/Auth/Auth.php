<?php

namespace Webkul\ApiResources\Auth;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: 'Authentication',
    operations: [
        new Post(
            name: 'auth_login',
            uriTemplate: '/admin/login',
            controller: 'Webkul\ApiResources\Http\Controllers\AuthController::login',
            security: '[]',
            openapi: new Model\Operation(
                summary: 'Admin Login',
                description: 'Authenticate admin user and get Sanctum token',
                tags: ['Authentication'],
                parameters: [],
                requestBody: new Model\RequestBody(
                    description: 'Admin credentials',
                    required: true,
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'required' => ['email', 'password', 'device_name'],
                                'properties' => [
                                    'email' => [
                                        'type' => 'string',
                                        'format' => 'email',
                                        'example' => 'admin@example.com',
                                        'description' => 'Admin email address',
                                    ],
                                    'password' => [
                                        'type' => 'string',
                                        'format' => 'password',
                                        'example' => 'admin123',
                                        'description' => 'Admin password',
                                    ],
                                    'device_name' => [
                                        'type' => 'string',
                                        'example' => 'Mobile',
                                        'description' => 'Device name for token tracking',
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ),
                responses: [
                    '200' => new Model\Response(
                        description: 'Login successful',
                        content: new \ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => [
                                            'type' => 'string',
                                            'example' => 'Logged in successfully',
                                        ],
                                        'token' => [
                                            'type' => 'string',
                                            'description' => 'Sanctum personal access token',
                                        ],
                                        'user' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => ['type' => 'integer'],
                                                'name' => ['type' => 'string'],
                                                'email' => ['type' => 'string', 'format' => 'email'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ]),
                    ),
                    '401' => new Model\Response(
                        description: 'Invalid credentials',
                        content: new \ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'error' => [
                                            'type' => 'string',
                                            'example' => 'Invalid credentials',
                                        ],
                                    ],
                                ],
                            ],
                        ]),
                    ),
                ],
            ),
        ),
        new Post(
            name: 'auth_logout',
            uriTemplate: '/admin/logout',
            controller: 'Webkul\ApiResources\Http\Controllers\AuthController::logout',
            security: '[{"sanctum":[]}]',
            openapi: new Model\Operation(
                summary: 'Admin Logout',
                description: 'Logout admin user and revoke current token',
                tags: ['Authentication'],
                security: [['sanctum' => []]],
                responses: [
                    '200' => new Model\Response(
                        description: 'Logout successful',
                        content: new \ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => [
                                            'type' => 'string',
                                            'example' => 'Logged out successfully',
                                        ],
                                    ],
                                ],
                            ],
                        ]),
                    ),
                    '401' => new Model\Response(
                        description: 'Unauthorized - Token required',
                    ),
                ],
            ),
        ),
        new Get(
            name: 'auth_get',
            uriTemplate: '/admin/get',
            controller: 'Webkul\ApiResources\Http\Controllers\AuthController::get',
            security: '[{"sanctum":[]}]',
            openapi: new Model\Operation(
                summary: 'Get Logged In Admin User Details',
                description: 'Retrieve logged in admin user\'s profile details including image information',
                tags: ['Authentication'],
                security: [['sanctum' => []]],
                responses: [
                    '200' => new Model\Response(
                        description: 'Admin details retrieved successfully',
                        content: new \ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => [
                                            'type' => 'string',
                                            'example' => 'Admin details retrieved successfully',
                                        ],
                                        'data' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => ['type' => 'integer'],
                                                'name' => ['type' => 'string'],
                                                'email' => ['type' => 'string', 'format' => 'email'],
                                                'status' => ['type' => 'integer'],
                                                'image' => ['type' => 'string', 'nullable' => true],
                                                'image_url' => ['type' => 'string', 'format' => 'uri', 'nullable' => true],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ]),
                    ),
                    '401' => new Model\Response(
                        description: 'Unauthorized - Token required or invalid',
                    ),
                ],
            ),
        ),
        new Post(
            name: 'auth_update',
            uriTemplate: '/admin/update',
            controller: 'Webkul\ApiResources\Http\Controllers\AuthController::update',
            security: '[{"sanctum":[]}]',
            openapi: new Model\Operation(
                summary: 'Update Admin User Profile',
                description: 'Update admin user\'s profile information (name, email, password)',
                tags: ['Authentication'],
                security: [['sanctum' => []]],
                requestBody: new Model\RequestBody(
                    description: 'Admin profile update fields',
                    required: true,
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'name' => [
                                        'type' => 'string',
                                        'example' => 'John Doe',
                                        'description' => 'Admin full name',
                                    ],
                                    'email' => [
                                        'type' => 'string',
                                        'format' => 'email',
                                        'example' => 'admin@example.com',
                                        'description' => 'Admin email address',
                                    ],
                                    'password' => [
                                        'type' => 'string',
                                        'format' => 'password',
                                        'example' => 'newpassword123',
                                        'description' => 'New password (minimum 8 characters)',
                                    ],
                                    'password_confirmation' => [
                                        'type' => 'string',
                                        'format' => 'password',
                                        'example' => 'newpassword123',
                                        'description' => 'Password confirmation must match password',
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ),
                responses: [
                    '200' => new Model\Response(
                        description: 'Admin profile updated successfully',
                        content: new \ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => [
                                            'type' => 'string',
                                            'example' => 'Admin profile updated successfully',
                                        ],
                                        'data' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => ['type' => 'integer'],
                                                'name' => ['type' => 'string'],
                                                'email' => ['type' => 'string', 'format' => 'email'],
                                                'status' => ['type' => 'integer'],
                                                'image' => ['type' => 'string', 'nullable' => true],
                                                'image_url' => ['type' => 'string', 'format' => 'uri', 'nullable' => true],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ]),
                    ),
                    '401' => new Model\Response(
                        description: 'Unauthorized - Token required or invalid',
                    ),
                    '422' => new Model\Response(
                        description: 'Validation failed - Invalid input data',
                    ),
                ],
            ),
        ),
        new Post(
            name: 'auth_forgot_password',
            uriTemplate: '/admin/forgot-password',
            controller: 'Webkul\ApiResources\Http\Controllers\AuthController::forgotPassword',
            security: '[]',
            openapi: new Model\Operation(
                summary: 'Admin Forgot Password',
                description: 'Send password reset link to admin email address',
                tags: ['Authentication'],
                requestBody: new Model\RequestBody(
                    description: 'Admin email for password reset',
                    required: true,
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'required' => ['email'],
                                'properties' => [
                                    'email' => [
                                        'type' => 'string',
                                        'format' => 'email',
                                        'example' => 'admin@example.com',
                                        'description' => 'Admin email address',
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ),
                responses: [
                    '200' => new Model\Response(
                        description: 'Password reset link sent successfully',
                        content: new \ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => [
                                            'type' => 'string',
                                            'example' => 'Password reset link has been sent to your email',
                                        ],
                                    ],
                                ],
                            ],
                        ]),
                    ),
                    '400' => new Model\Response(
                        description: 'Unable to send password reset link',
                    ),
                    '422' => new Model\Response(
                        description: 'Validation failed - Invalid email',
                    ),
                ],
            ),
        ),
    ],
)]
class Auth
{
    #[Groups(['read'])]
    public $id;

    #[Groups(['read', 'write'])]
    public $name;

    #[Groups(['read', 'write'])]
    public $email;

    #[Groups(['write'])]
    public $password;

    #[Groups(['write'])]
    public $password_confirmation;

    #[Groups(['write'])]
    public $device_name;

    #[Groups(['read'])]
    public $token;

    #[Groups(['read'])]
    public $message;

    #[Groups(['read'])]
    public $data;

    #[Groups(['read'])]
    public $error;

    #[Groups(['read'])]
    public $status;

    #[Groups(['read'])]
    public $image;

    #[Groups(['read'])]
    public $image_url;
}
