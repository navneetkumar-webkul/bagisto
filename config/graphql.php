<?php

return [
    'error_formatter' => [\Webkul\ApiResources\GraphQL\ErrorFormatter::class, '__invoke'],
    'errors_handler' => [\Webkul\ApiResources\GraphQL\ErrorFormatter::class, '__invoke'],
    'middleware' => [],
    
    'schemas' => [
        'default' => [
            'query' => [
                // Add queries here
            ],
            'mutation' => [
                \Webkul\ApiResources\GraphQL\Mutations\LoginMutation::class,
            ],
        ],
    ],

    'types' => [
        \Webkul\ApiResources\GraphQL\Types\JsonType::class,
    ],
];

