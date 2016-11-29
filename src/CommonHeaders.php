<?php

namespace ExpressiveAssets;

/**
 * Class CommonHeaders
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class CommonHeaders
{
    /**
     * CommonHeaders constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config['expressive-assets']['common_headers'];
    }

    /**
     * get
     *
     * @return array
     */
    public function get()
    {
        return $this->config;
    }
}
