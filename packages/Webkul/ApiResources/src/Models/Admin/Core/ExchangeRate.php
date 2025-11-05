<?php

namespace Webkul\ApiResources\Models\Admin\Core;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Exchange Rate resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class ExchangeRate extends \Webkul\Core\Models\CurrencyExchangeRate {}
