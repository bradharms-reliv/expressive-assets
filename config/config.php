<?php
/**
 * config.php
 */
return [
    'dependencies' => [
        'factories' => [
            \ExpressiveAssets\AssetController::class
            => \ExpressiveAssets\AssetControllerFactory::class
        ],
    ],
    'routes' => [
        'expressive-assets.public' => [
            'name' => 'expressive-assets.public',
            'path' => '/expressive-assets/{fileName:.*}',
            'middleware' => \ExpressiveAssets\AssetController::class,
            'options' => [],
            'allowed_methods' => ['GET'],
            /* expressive asset config */
            'expressive-asset' => [
                'directory' => __DIR__ . '/../public',
                'headers' => \ExpressiveAssets\CommonHeaders::get()
            ],
        ],
    ],
];
