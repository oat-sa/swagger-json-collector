<?php
/**
 * Copyright (c) 2016.
 *
 * @author Alexander Zagovorichev <zagovorichev@gmail.com>
 */

namespace swaggerCollector;


use swaggerCollector\helper\RestDocHelper;

class DocsCollector
{
    /**
     * @var Swagger2_0
     */
    private $swagger;

    /**
     * Template for documentation
     *
     * @var \stdClass
     */
    private $blank;

    public function __construct()
    {
        $this->swagger = new Swagger2_0();
        $this->blank = RestDocHelper::getJsonFromFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'rest.json');
    }

    public function generate(array $paths)
    {
        $documentation = $this->blank;

        foreach (array_unique($paths) as $path) {
            $documentation = $this->swagger->append($documentation, RestDocHelper::getJsonFromFile($path));
        }

        return $documentation;
    }

}
