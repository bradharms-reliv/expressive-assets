<?php

namespace ExpressiveAssets;

/**
 * Class ModuleConfig
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ModuleConfig
{
    /**
     * __invoke
     *
     * @return mixed
     */
    public function __invoke()
    {
        return require(__DIR__ . '/../config/config.php');
    }
}
