<?php

return [
    'error_formatter' => [\Webkul\ApiResources\GraphQL\ErrorFormatter::class, '__invoke'],
    'errors_handler'  => [\Webkul\ApiResources\GraphQL\ErrorFormatter::class, '__invoke'],
    'middleware'      => [],

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

    'namespaces' => [
        'mutations_namespace' => 'Webkul\\ApiResources\\GraphQL\\Mutations',
        'queries_namespace'   => 'Webkul\\ApiResources\\GraphQL\\Queries',
        'types_namespace'     => 'Webkul\\ApiResources\\GraphQL\\Types',
        'interfaces_namespace'=> 'Webkul\\ApiResources\\GraphQL\\Interfaces',
        'unions_namespace'    => 'Webkul\\ApiResources\\GraphQL\\Unions',
        'scalars_namespace'   => 'Webkul\\ApiResources\\GraphQL\\Scalars',
        'enums_namespace'     => 'Webkul\\ApiResources\\GraphQL\\Enums',
    ],
];
