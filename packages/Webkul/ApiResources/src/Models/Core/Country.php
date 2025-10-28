<?php

namespace Webkul\ApiResources\Models\Core;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ApiResource(
    routePrefix: '/api/v1/admin',
    operations: [
        new GetCollection(),
        new Get(),
    ]
)]
class Country extends \Webkul\Core\Models\Country
{
    #[ApiProperty(readableLink: true)]
    public function getStates()
    {
        return $this->states;
    }

    #[ApiProperty(readableLink: true)]
    public function getTranslations()
    {
        return $this->translations;
    }

    public function states()
    {
        return $this->hasMany(CountryState::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CountryTranslation::class);
    }
}
