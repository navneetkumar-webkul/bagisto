<?php

namespace Webkul\ApiResources\Models\Core;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
    ]
)]
class CountryTranslation extends \Webkul\Core\Models\CountryTranslation
{

}
