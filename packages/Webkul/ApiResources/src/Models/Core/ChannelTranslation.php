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
class ChannelTranslation extends \Webkul\Core\Models\ChannelTranslation
{

}
