<?php

namespace ExpressiveAssets;

use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Expressive\Router\RouteResult;
use Psr\Http\Message\ServerRequestInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Zend\Stratigility\Next;

/**
 * Class AssetController
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class AssetController implements MiddlewareInterface
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
     * @var CommonHeaders
     */
    protected $commonHeaders;

    /**
     * AssetController constructor.
     *
     * @param array         $config
     * @param CommonHeaders $commonHeaders
     */
    public function __construct(
        $config,
        CommonHeaders $commonHeaders
    ) {
        $this->config = $config;
        $this->commonHeaders = $commonHeaders;
    }

    /**
     * getConfig
     *
     * @param ServerRequestInterface $request
     *
     * @return Config
     */
    protected function getConfig(ServerRequestInterface $request)
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
        $directory = $config->get('directory');

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

        $headerMap = $config->get('headers', $this->commonHeaders->get());

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
     * @param ServerRequestInterface $request
     *
     * @return string|null
     */
    protected function getFileName(ServerRequestInterface $request)
    {
        return $request->getAttribute(
            self::PARAM_FILE_PATH
        );
    }

    /**
     * New expressive v2 "single pass" interface
     *
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate){
        $delegate = $delegate instanceof Next
            ? $delegate
            : function ($request) use ($delegate) {
                return $delegate->process($request);
            };

        return $this->__invoke($request, new Response(), $delegate);
    }

    /**
     * Legacy Expressive v1 "double pass "interface
     *
     * @param ServerRequestInterface           $request
     * @param ResponseInterface $response
     * @param callable|null     $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $fileName = $this->getFileName($request);

        if (empty($fileName)) {
            return $response->withStatus(404);
        }

        $config = $this->getConfig($request);

        $filePath = $this->getPath($config, $fileName);



        if (empty($filePath)) {
            $onNotFound = $config->get('not-found-status', 404);

            return $response->withStatus($onNotFound);
        }

        $headers = $this->getHeaders($config, $fileName);

        $alwaysHeaders = $config->get('always-headers');

        if (!empty($alwaysHeaders)) {
            $headers = array_merge($headers, $alwaysHeaders);
        }

        $body = $response->getBody();

        $body->write(file_get_contents($filePath));

        foreach ($headers as $headerKey => $value) {
            $response = $response->withHeader($headerKey, $value);
        }

        return $response->withBody($body);
    }
}
