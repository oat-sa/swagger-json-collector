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

    /**
     * Load json from various formats
     *
     * @param mixed $resource - file containing JSON|JSON string|JSON object|PHP array
     *
     * @return array
     * @throws RestApiDocsException
     */
    public static function loadJson($resource) {

        // value could be a JSON object
        if($resource instanceof \stdClass) {
            return $resource;
        }

        // value could be an array -> convert to object
        if(is_array($resource)) {
            return json_decode(json_encode($resource));
        }

        // value now needs to be a string, either a file path or actual JSON
        if(!is_string($resource)) {
            throw new RestApiDocsException('Input does not have any of the expected formats');
        }

        // value is a file
        if(is_file($resource)) {
            return json_decode(file_get_contents($resource));
        }

        // value is JSON string
        $jsonObj = json_decode($resource);
        if(json_last_error() === JSON_ERROR_NONE) {
            return $jsonObj;
        }

        throw new RestApiDocsException('Input is not a valid JSON resource');
    }

    /**
     * Legacy function kept for backwards compatibility
     *
     * @param $path
     *
     * @return array
     */
    public static function getJsonFromFile($path)
    {
        return self::loadJson($path);
    }
}
