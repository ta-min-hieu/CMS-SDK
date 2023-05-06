<?php
/**
 * Created by PhpStorm.
 *
 * Date: 10/11/2016
 * Time: 6:03 PM
 */

namespace awesome\backend\helpers;


class FileHelper
{
    /**
     * Check if a file exists
     *
     * @param string $file the file with path in URL format
     *
     * @return bool
     */
    public static function fileExists($file)
    {
        $file = str_replace('/', DIRECTORY_SEPARATOR, $file);
        return file_exists($file);
    }
}