<?php

namespace Webkul\ApiResources\Models\Core;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Exchange Rate resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class ExchangeRate extends \Webkul\Core\Models\CurrencyExchangeRate
{
}
