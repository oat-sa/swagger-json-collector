<?php
/**
 * Copyright (c) 2016.
 *
 * @author Alexander Zagovorichev <zagovorichev@gmail.com>
 */

namespace swaggerCollector\tests;


use PHPUnit_Framework_TestCase;
use swaggerCollector\DocsCollector;
use swaggerCollector\RestApiDocsException;
use swaggerCollector\Swagger2_0;

class CompilerTest extends PHPUnit_Framework_TestCase
{

    private $samplesPath;

    public function setUp()
    {
        $this->samplesPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'samples' . DIRECTORY_SEPARATOR;
    }


    /**
     * @expectedException \swaggerCollector\RestApiDocsException
     * @expectedExceptionMessage Incorrect file structure
     */
    public function testCombinerFailure()
    {
        $paths = [];
        foreach (['failure.json', 'lti.json', 'QtiItem.json'] as $fileName) {
            $paths[] = $this->samplesPath . $fileName;
        }

        $collector = new DocsCollector();
        $collector->generate($paths);
    }

    public function testCombiner()
    {
        $paths = [];
        foreach (['lti.json', 'QtiItem.json'] as $fileName) {
            $paths[] = $this->samplesPath . $fileName;
        }

        $collector = new DocsCollector();
        $docs = $collector->generate($paths);

        $swagger = new Swagger2_0();
        try {
            $swagger->validate($docs);
        } catch (RestApiDocsException $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }
}
