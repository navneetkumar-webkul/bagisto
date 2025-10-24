<?php

namespace Webkul\ApiResources\Models\Customer;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ApiResource]
class Customer extends \Webkul\Customer\Models\Customer
{
    /**
     * Get customer addresses.
     */
    #[ApiProperty(readableLink: true)]
    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }
}
