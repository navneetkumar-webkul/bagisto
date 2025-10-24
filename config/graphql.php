<?php

return [
    'error_formatter' => [\Webkul\ApiResources\GraphQL\ErrorFormatter::class, '__invoke'],
    'errors_handler' => [\Webkul\ApiResources\GraphQL\ErrorFormatter::class, '__invoke'],
    'middleware' => [],
];

