<?php

return [
    'route' => [
        'prefix' => 'api',
    ],
    'servers' => null,
    'pagination' => [
        'meta_key' => null, // Default: page
        'page_key' => null, // Default: number
        'limit_key' => null, // Default: size
        'without_nested_meta' => null, // Default: false
        'default_per_page' => null, // Default: 15
        'meta_case' => null, // Default the meta information about the page uses camel-case keys. Options:  snake or dash
    ],
];
