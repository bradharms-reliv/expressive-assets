<?php
/**
 * config.php
 */
return [
    'dependencies' => [
        'factories' => [
            \ExpressiveAssets\AssetController::class
            => \ExpressiveAssets\AssetControllerFactory::class,
            \ExpressiveAssets\CommonHeaders::class
            => \ExpressiveAssets\CommonHeadersFactory::class
        ],
    ],
    /* TEST
    'routes' => [
        'expressive-assets.public' => [
            'name' => 'expressive-assets.public',
            'path' => '/expressive-assets/{fileName:.*}',
            'middleware' => \ExpressiveAssets\AssetController::class,
            'options' => [],
            'allowed_methods' => ['GET'],
            // expressive asset config
            'expressive-asset' => [
                'directory' => __DIR__ . '/../public',
                'headers' => [
                    'css' => [
                        'content-type' => 'text/css'
                    ],
                    'html' => [
                        'content-type' => 'text/html'
                    ],
                    'js' => [
                        'content-type' => 'application/javascript'
                    ],
                ],
            ],
        ],
    ],
    */

    'expressive-assets' => [
        'common_headers' => require(__DIR__ . '/common_headers.php'),
    ],
];
