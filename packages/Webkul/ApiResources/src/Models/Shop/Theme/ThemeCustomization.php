<?php

namespace Webkul\ApiResources\Models\Admin\Theme;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Theme Customization resource',
    routePrefix: '/api/v1/shop',
)]
class ThemeCustomization extends \Webkul\Theme\Models\ThemeCustomization
{
}
