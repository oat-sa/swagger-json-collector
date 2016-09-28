<?php
/**
 * Copyright (c) 2016.
 *
 * @author Alexander Zagovorichev <zagovorichev@gmail.com>
 */

namespace swaggerCollector\helper;


use swaggerCollector\RestApiDocsException;

class RestDocHelper
{
    private static function checkFile($path = '')
    {
        if (!file_exists($path)) {
            throw new RestApiDocsException('File with documentation not found');
        }
    }
    
    public static function getJsonFromFile($path = '')
    {
        self::checkFile($path);
        return json_decode(file_get_contents($path));
    }
}
