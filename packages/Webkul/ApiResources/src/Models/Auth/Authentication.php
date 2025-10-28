<?php

namespace Webkul\ApiResources\Models\Auth;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use Webkul\ApiResources\State\AuthProcessor;

#[ApiResource(
    description: 'Authentication resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')",
    operations: [
        new Post(
            uriTemplate: '/authentications',
            processor: AuthProcessor::class,
        ),
    ],
)]
class Authentication
{
    #[ApiProperty(identifier: true, writable: false, readable: true)]
    public ?string $id = null;

    #[ApiProperty(writable: false, readable: true)]
    public ?string $token = null;

    #[ApiProperty(writable: false, readable: true)]
    public ?string $message = null;

    #[ApiProperty(writable: false, readable: true)]
    public ?User $user = null;

    #[ApiProperty(writable: true, readable: false)]
    public ?string $email = null;

    #[ApiProperty(writable: true, readable: false)]
    public ?string $password = null;

    #[ApiProperty(writable: true, readable: false)]
    public ?string $deviceName = 'api';

    public function __construct(
        ?string $id = null,
        ?string $email = null,
        ?string $password = null,
        ?string $deviceName = null,
        ?string $token = null,
        ?string $message = null,
        ?User $user = null,
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->deviceName = $deviceName ?? 'api';
        $this->token = $token;
        $this->message = $message;
        $this->user = $user;
    }
}

class User
{
    #[ApiProperty]
    public ?int $id = null;

    #[ApiProperty]
    public ?string $name = null;

    #[ApiProperty]
    public ?string $email = null;

    public function __construct(
        ?int $id = null,
        ?string $name = null,
        ?string $email = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }
}
