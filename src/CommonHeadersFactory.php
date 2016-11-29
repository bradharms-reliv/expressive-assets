<?php

namespace ExpressiveAssets;

use Interop\Container\ContainerInterface;

/**
 * Class CommonHeadersFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class CommonHeadersFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return CommonHeaders
     */
    public function __invoke($container)
    {
        $config = $container->get('Config');
        return new CommonHeaders($config);
    }
}
