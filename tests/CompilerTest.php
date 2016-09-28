<?php
/**
 * Copyright (c) 2016.
 *
 * @author Alexander Zagovorichev <zagovorichev@gmail.com>
 */

namespace swaggerCollector\tests;


use PHPUnit_Framework_TestCase;
use swaggerCollector\DocsCollector;

class CompilerTest extends PHPUnit_Framework_TestCase
{

    private $samplesPath;

    public function setUp()
    {
        $this->samplesPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'samples' . DIRECTORY_SEPARATOR;
    }

    public function testCombiner()
    {
        $paths = [];
        foreach (['failure.json', 'lti.json', 'QtiItem.json'] as $fileName) {
            $paths[] = $this->samplesPath . $fileName;
        }

        $collector = new DocsCollector();
        $docs = $collector->generate($paths);
        var_dump($docs);
    }
}
