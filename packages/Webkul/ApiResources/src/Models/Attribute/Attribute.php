<?php

namespace Webkul\ApiResources\Models\Attribute;

use ApiPlatform\Metadata\ApiResource;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use GraphQL\Error\UserError;
use Webkul\ApiResources\Http\Requests\AttributeFormRequest;


#[ApiResource (
    rules: AttributeFormRequest::class,
    security: "is_granted('ROLE_ADMIN')"
)]
class Attribute extends \Webkul\Attribute\Models\Attribute
{
    protected $hidden = ['translation'];

    public static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            if (static::where('code', $model->code)->exists()) {
                // Throw GraphQL-friendly error
                throw new UserError("The attribute code '{$model->code}' already exists.");
            }
        });
    }

    /**
     * Get the options.
     */
    public function options(): HasMany
    {
        return $this->hasMany(AttributeOption::class);
    }
}
