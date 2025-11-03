<?php

namespace Webkul\ApiResources\Models\Admin\GDPR;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    shortName: 'GdprDataRequest',
    description: 'GDPR Data Request resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class GDPRDataRequest extends \Webkul\GDPR\Models\GDPRDataRequest {}
