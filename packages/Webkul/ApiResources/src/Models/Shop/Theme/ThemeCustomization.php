<?php

namespace Webkul\ApiResources\Models\Shop\Theme;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Theme Customization for the Shop',
    routePrefix: '/api/v1/shop',
    operations: [
        new \ApiPlatform\Metadata\Get(openapi: new \ApiPlatform\OpenApi\Model\Operation(null, ['Shop - Theme'], null, 'Theme Customization for the Shop', 'Retrieve theme customization settings used by the shop UI.\n\nThis resource exposes read-only endpoints to fetch theme-related configuration used by the storefront.')),
        new \ApiPlatform\Metadata\GetCollection(openapi: new \ApiPlatform\OpenApi\Model\Operation(null, ['Shop - Theme'], null, 'Theme Customization for the Shop (collection)', 'Retrieve a collection of theme customization settings used by the shop UI.')),
    ],
)]
class ThemeCustomization extends \Webkul\Theme\Models\ThemeCustomization {}
