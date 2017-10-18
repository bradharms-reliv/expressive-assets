# THIS HAS BEEN DEPRICATED IN FAVOR OF https://github.com/reliv/serve-static.

Simple asset loading using Zend Expressive middleware

## Configuration ##

```
'routes' => [
    // Asset route name MUST be used as the key
    'expressive-assets.public' => [
        // YOUR ROUTE NAME
        'name' => 'expressive-assets.public',
        // Path MUST contain fileName route param
        'path' => '/expressive-assets/{fileName:.*}',
        'middleware' => \ExpressiveAssets\AssetController::class,
        'options' => [],
        'allowed_methods' => ['GET'],
        /* expressive asset config */
        'expressive-asset' => [
            // Directory where assets are publicly available
            'directory' => __DIR__ . '/../public',
            // File extension to response headers, 
            // If headers value is not supplied 
            // then \ExpressiveAssets\CommonHeaders will be used
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
            ]
        ]
    ],
],
```
