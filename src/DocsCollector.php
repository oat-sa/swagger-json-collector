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
        $this->blank = RestDocHelper::loadJson(dirname(__DIR__) . '/doc/rest.json');
    }


    /**
     * @param array $resources
     *
     * @return \stdClass
     */
    public function generate(array $resources)
    {
        $documentation = $this->blank;
        foreach (array_unique($resources) as $resource) {
            $documentation = $this->swagger->append($documentation, RestDocHelper::loadJson($resource));
        }
        return $documentation;
    }

}
