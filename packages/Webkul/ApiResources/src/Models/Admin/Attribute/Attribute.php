<?php

namespace Webkul\ApiResources\Models\Admin\Attribute;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use GraphQL\Error\UserError;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\ApiResources\Http\Requests\AttributeFormRequest;

#[ApiResource(
    shortName: 'Attribute',
    description: 'Product attribute resource',
    rules: AttributeFormRequest::class,
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Attribute extends \Webkul\Attribute\Models\Attribute
{
    protected $hidden = ['translation'];

    public static function boot()
    {
        parent::boot();

        static::creating(function (EloquentModel $model) {
            if (static::where('code', $model->code)->exists()) {
                // Throw GraphQL-friendly error
                throw new UserError("The attribute code '{$model->code}' already exists.");
            }
        });
    }

    #[ApiProperty(readableLink: true)]
    public function options(): HasMany
    {
        return $this->hasMany(AttributeOption::class);
    }
}
