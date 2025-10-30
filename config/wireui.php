<?php

$defaultConfig = require base_path('vendor/wireui/wireui/src/config.php');

// Add fallback configuration for "center" component
$defaultConfig['center'] = [
    'default' => [
        'align' => 'center',
    ],
    'packs' => [
        'aligns' => 'WireUi\Components\Modal\WireUi\Align',
    ],
];

return array_replace_recursive($defaultConfig, [
    'script' => [
        'defer' => true,
    ],
    'style' => [
        'theme' => 'default',
    ],
    'notifications' => [
        'default' => [
            'position' => 'top-right',
        ],
        'timeout' => 3000,
        'stack' => true,
    ],
    'loading' => [
        'icon' => 'default',
    ],
    'assets' => [
        'css' => true,
        'js' => true,
    ],
]);
