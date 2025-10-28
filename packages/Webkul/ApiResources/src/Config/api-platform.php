<?php

/*
 * This file is part of the API Platform project.
 */
declare(strict_types=1);

use ApiPlatform\Metadata\UrlGeneratorInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\Serializer\NameConverter\SnakeCaseToCamelCaseNameConverter;

return [
    'title' => 'Bagisto API Resources',
    'description' => 'Bagisto provides comprehensive API solutions to enable integration and extension of its e-commerce platform. These APIs facilitate the development of various applications, including mobile apps, third-party integrations, and headless commerce solutions.',
    'version' => '1.0.0',
    'show_webby' => true,

    'routes' => [
        'domain' => null,
    ],

    'resources' => [
        base_path('packages/Webkul/ApiResources/'),
    ],

    'formats' => [
        'jsonld' => ['application/ld+json'],
        'json' => ['application/json'],
        'html' => ['text/html'],
    ],

    'patch_formats' => [
        'json' => ['application/merge-patch+json'],
    ],

    'docs_formats' => [
        'jsonld' => ['application/ld+json'],
        // 'jsonapi' => ['application/vnd.api+json'],
        'jsonopenapi' => ['application/vnd.openapi+json'],
        'html' => ['text/html'],
    ],

    'error_formats' => [
        'jsonproblem' => ['application/problem+json'],
    ],

    'defaults' => [
        'pagination_enabled' => true,
        'pagination_partial' => false,
        'pagination_client_enabled' => false,
        'pagination_client_items_per_page' => false,
        'pagination_client_partial' => false,
        'pagination_items_per_page' => 30,
        'pagination_maximum_items_per_page' => 30,
        'route_prefix' => '/api/v1',
        'middleware' => ['auth:sanctum'],
    ],

    'pagination' => [
        'page_parameter_name' => 'page',
        'enabled_parameter_name' => 'pagination',
        'items_per_page_parameter_name' => 'itemsPerPage',
        'partial_parameter_name' => 'partial',
    ],

    'graphql' => [
        'enabled' => true,
        'graphiql' => [
            'enabled' => env('API_PLATFORM_GRAPHIQL_ENABLED', true),
        ],
        'graphql_playground' => [
            'enabled' => env('API_PLATFORM_GRAPHQL_PLAYGROUND_ENABLED', true),
        ],
        'nesting_separator' => '__',
        'introspection' => ['enabled' => true],
        'max_query_complexity' => 500,
        'max_query_depth' => 200,
        'route_prefix' => '/api/v1/graphql',
        'middleware' => [
            \Webkul\ApiResources\Http\Middleware\GraphQLAuthMiddleware::class.':sanctum',
        ],
    ],
    // set to null if you want to keep snake_case
    'name_converter' => SnakeCaseToCamelCaseNameConverter::class,

    'exception_to_status' => [
        AuthenticationException::class => 401,
        AuthorizationException::class => 403,
    ],

    'swagger_ui' => [
        'enabled' => true,
        'apiKeys' => [
            'sanctum' => [
                'name' => 'Authorization',
                'type' => 'header',
                'in' => 'header',
                'description' => 'Send "Bearer {token}" — Sanctum personal access token',
            ],
        ],
        'http_auth' => [
            'sanctum' => [
                'scheme' => 'bearer',
                'bearerFormat' => 'JWT',
            ],
        ],
    ],

    'url_generation_strategy' => UrlGeneratorInterface::ABS_PATH,

    'serializer' => [
        'hydra_prefix' => false,
    ],

    'cache' => 'file',

    'validation' => [
        'enabled' => true,
    ],

    // 'http_cache' => [
    //     'etag' => true,
    //     'max_age' => 3600,
    //     'shared_max_age' => 3600,
    //     'vary' => ['Accept', 'Authorization'],
    //     'public' => true,
    //     'stale_while_revalidate' => 60,
    //     'stale_if_error' => 3600,
    //     'invalidation' => [
    //         'urls' => [],
    //         'scoped_clients' => [],
    //         'max_header_length' => 7500,
    //         'request_options' => [],
    //         // 'purger' => ApiPlatform\HttpCache\SouinPurger::class,
    //     ],
    // ],
];
