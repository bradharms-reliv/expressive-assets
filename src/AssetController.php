<?php

namespace ExpressiveAssets;

use Psr\Http\Message\ResponseInterface;
use Zend\Expressive\Router\RouteResult;
use Zend\Stratigility\Http\Request;

/**
 * Class AssetController
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class AssetController
{
    /**
     *
     */
    const PARAM_FILE_PATH = 'fileName';

    /**
     * @var array
     */
    protected $defaultHeaders
        = [
            'content-type' => 'text/plain'
        ];

    /**
     * @var array
     */
    protected $config;

    /**
     * AssetController constructor.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * getConfig
     *
     * @param Request $request
     *
     * @return Config
     */
    protected function getConfig(Request $request)
    {
        /** @var RouteResult $routeResult */
        $routeResult = $request->getAttribute(
            RouteResult::class
        );

        $routeName = $routeResult->getMatchedRouteName();

        if (!array_key_exists($routeName, $this->config['routes'])) {
            return new Config();
        }

        $routeConfig = $this->config['routes'][$routeName];

        if (!array_key_exists('expressive-asset', $routeConfig)) {
            return new Config();
        }

        $config = new Config($routeConfig['expressive-asset']);

        return $config;
    }

    /**
     * getPath
     *
     * @param Config $config
     * @param string $fileName
     *
     * @return null|string
     */
    protected function getPath(Config $config, $fileName)
    {
        $directory= $config->get('directory');

        if (empty($directory)) {
            return null;
        }

        $filePath = $directory . '/' . $fileName;

        $filePath = realpath($filePath);

        // make sure file is real and is secure
        if (strpos($filePath, realpath($directory)) === 0 && is_file($filePath)) {
            return $filePath;
        }

        return null;
    }

    /**
     * getHeaders
     *
     * @param Config $config
     * @param        $fileName
     *
     * @return mixed
     */
    protected function getHeaders(Config $config, $fileName)
    {
        $fileExtension = $this->getFileExtension($fileName);

        if (empty($fileExtension)) {
            return $this->defaultHeaders;
        }

        $headerMap = $config->get('headers', []);

        if (array_key_exists($fileExtension, $headerMap)) {
            return $headerMap[$fileExtension];
        }

        return $this->defaultHeaders;
    }

    /**
     * getFileExtension
     *
     * @param string $fileName
     *
     * @return null
     */
    protected function getFileExtension($fileName)
    {
        $parts = pathinfo($fileName);

        if (!array_key_exists('extension', $parts)) {
            return null;
        }

        return $parts['extension'];
    }

    /**
     * getFileName
     *
     * @param Request $request
     *
     * @return string|null
     */
    protected function getFileName(Request $request)
    {
        return $request->getAttribute(
            self::PARAM_FILE_PATH
        );
    }

    /**
     * __invoke
     *
     * @param Request           $request
     * @param ResponseInterface $response
     * @param callable|null     $next
     *
     * @return ResponseInterface
     */
    public function __invoke(Request $request, ResponseInterface $response, callable $next = null)
    {
        $fileName = $this->getFileName($request);

        if (empty($fileName)) {
            return $response->withStatus(404);
        }

        $config = $this->getConfig($request);

        $filePath = $this->getPath($config, $fileName);

        if (empty($filePath)) {
            return $response->withStatus(404);
        }

        $headers = $this->getHeaders($config, $fileName);

        $body = $response->getBody();

        $body->write(file_get_contents($filePath));

        foreach ($headers as $headerKey => $value) {
            $response = $response->withHeader($headerKey, $value);
        }

        return $response->withBody($body);
    }
}
