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
     * @var array
     */
    protected static $values
        = [
            'css' => ['content-type' => 'text/css'],
            'doc' => ['content-type' => 'application/msword'],
            'exe' => ['content-type' => 'application/octet-stream'],
            'gif' => ['content-type' => 'image/gif'],
            'html' => ['content-type' => 'text/html'],
            'jpeg' => ['content-type' => 'image/jpg'],
            'jpg' => ['content-type' => 'image/jpg'],
            'js' => ['content-type' => 'application/javascript'],
            'pdf' => ['content-type' => 'application/pdf'],
            'php' => ['content-type' => 'text/plain'],
            'png' => ['content-type' => 'image/png'],
            'ppt' => ['content-type' => 'application/vnd.ms-powerpoint'],
            'txt' => ['content-type' => 'text/plain'],
            'xls' => ['content-type' => 'application/vnd.ms-excel'],
            'zip' => ['content-type' => 'application/zip'],
        ];

    /**
     * get
     *
     * @return array
     */
    public static function get()
    {
        return self::$values;
    }
}
