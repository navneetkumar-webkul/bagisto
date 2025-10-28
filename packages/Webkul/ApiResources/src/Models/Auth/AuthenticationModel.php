<?php

namespace Webkul\ApiResources\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use Webkul\ApiResources\State\AuthProcessor;

#[ApiResource(
    description: 'Authentication Model resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')",
    operations: [
        new Post(
            uriTemplate: '/authentications',
            processor: AuthProcessor::class,
        ),
    ],
)]
class AuthenticationModel extends Model
{
    protected $table = 'authentications';
    protected $fillable = ['email', 'password', 'device_name', 'token', 'message', 'user_id'];
    public $timestamps = false;

    #[ApiProperty(writable: false, readable: true)]
    public ?string $token = null;

    #[ApiProperty(writable: false, readable: true)]
    public ?string $message = null;

    #[ApiProperty(writable: true, readable: false)]
    public ?string $email = null;

    #[ApiProperty(writable: true, readable: false)]
    public ?string $password = null;

    #[ApiProperty(writable: true, readable: false)]
    public ?string $deviceName = null;
}
