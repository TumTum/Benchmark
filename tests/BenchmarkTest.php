<?php

use UCSDMath\Testing\Benchmark;

class BenchmarkTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Benchmark
     */
    private $subject;

    /**
     * Bevor every Test make a new Object
     */
    protected function setUp()
    {
        $this->subject = new Benchmark();
    }

    /**
     * @param $expected
     * @param $actual
     */
    protected function assertLines($expected, $actual)
    {
        $actual = explode(Benchmark::CRLF, $actual);

        for ($i = 0; $i < count($actual); $i++) {
            $this->assertRegExp('/' . $expected[$i] . '/', $actual[$i], 'Failed line: ' . ($i + 1));
        }
    }
}
