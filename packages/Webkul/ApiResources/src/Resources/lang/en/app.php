<?php

return [
    'auth' => [
        'login' => [
            'invalid_credentials' => 'Invalid email or password.',
            'success'             => 'Login successful.',
        ],
        'logout' => [
            'success' => 'Logout successful.',
        ],
        'get' => [
            'success' => 'User details retrieved successfully.',
        ],
        'update' => [
            'success' => 'Profile updated successfully.',
        ],
        'forgot_password' => [
            'link_sent'      => 'Password reset link has been sent to your email.',
            'user_not_found' => 'User not found.',
            'failed'         => 'Failed to send password reset link.',
            'try_again'      => 'Please try again later.',
        ],
    ],
    'validations' => [
        'channel' => [
            // Code field
            'code_required' => 'Channel code is required.',
            'code_unique'   => 'Channel code must be unique.',

            // Name field
            'name_required' => 'Channel name is required.',

            // Locale fields
            'default_locale_id_required' => 'Default locale is required.',
            'default_locale_id_exists'   => 'The selected default locale does not exist.',
            'locales_required'           => 'At least one locale must be selected.',
            'locales_exists'             => 'One or more selected locales do not exist.',

            // Inventory sources
            'inventory_sources_required' => 'At least one inventory source must be selected.',
            'inventory_sources_exists'   => 'One or more selected inventory sources do not exist.',

            // Category
            'root_category_id_required' => 'Root category is required.',
            'root_category_id_exists'   => 'The selected root category does not exist.',

            // Currency fields
            'currencies_required'       => 'At least one currency must be selected.',
            'currencies_exists'         => 'One or more selected currencies do not exist.',
            'base_currency_id_required' => 'Base currency is required.',
            'base_currency_id_exists'   => 'The selected base currency does not exist.',

            // Meta fields
            'meta_title_required'       => 'Meta title is required.',
            'meta_keywords_required'    => 'Meta keywords are required.',
            'meta_description_required' => 'Meta description is required.',

            // Hostname
            'hostname_unique' => 'Hostname must be unique.',
        ],
    ],
    'rest-api' => [
        'admin' => [
            'catalog' => [
                'products' => [
                    'type'                  => 'Type',
                    'attribute-family'      => 'Attribute Family',
                    'sku'                   => 'SKU',
                    'super-attributes'      => 'Super Attributes',
                    'error'                 => [
                        'type-required'                    => 'The type field is required.',
                        'type-invalid'                     => 'The selected type is invalid. Please select a valid product type.',
                        'attribute-family-required'        => 'The attribute family field is required.',
                        'attribute-family-exists'          => 'The selected attribute family does not exist.',
                        'sku-required'                     => 'The SKU field is required.',
                        'sku-unique'                       => 'The SKU must be unique.',
                        'sku-invalid-format'               => 'The SKU format is invalid. It must contain only alphanumeric characters, hyphens, and underscores.',
                        'super-attributes-array'           => 'The super attributes must be an array.',
                        'super-attributes-min'             => 'The super attributes must have at least one item.',
                        'configurable-error'               => 'Configurable products must have at least one super attribute.',
                    ],
                    'create-success' => 'Product created successfully.',
                    'update-success' => 'Product updated successfully.',
                    'delete-success' => 'Product deleted successfully.',
                ],
            ],
        ],
    ],
];
