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
     * Test Standard Benchmark Output
     */
    public function testBenchmarkOutputAsHTMLComment()
    {
        $this->subject->start();
        $actual = $this->subject->stop();

        $expected[] = '';
        $expected[] = '    <!-- ======== \[ BENCHMARK DATA \] ========';
        $expected[] = '    Elapsed Time: 0ms';
        $expected[] = '    Elapsed Time: [0-9E.-]+ \(micro\)';
        $expected[] = '    Memory Usage: [0-9.-]+[B|Kb|Mb|Gb|Tb]+';
        $expected[] = '     Peak Memory: [0-9.-]+[B|Kb|Mb|Gb|Tb]+ \(or [0-9]+ bytes\)';
        $expected[] = '            Date: \d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d';
        $expected[] = '    ===================================== -->';

        $this->assertLines($expected, $actual);
    }

    /**
     * Test Standard Benchmark Output as Display
     */
    public function testBenchmarkOutputDisplayAsHTML()
    {
        $this->subject->start();
        $actual = $this->subject->stop(true);

        $expected[] = '';
        $expected[] = '<pre id="bench">';
        $expected[] = '======== \[ BENCHMARK DATA \] ========';
        $expected[] = '    Elapsed Time: 0ms';
        $expected[] = '    Elapsed Time: [0-9E.-]+ \(micro\)';
        $expected[] = '    Memory Usage: [0-9.-]+[B|Kb|Mb|Gb|Tb]+';
        $expected[] = '     Peak Memory: [0-9.-]+[B|Kb|Mb|Gb|Tb]+ \(or [0-9]+ bytes\)';
        $expected[] = '            Date: \d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d';
        $expected[] = '    ===================================== ';
        $expected[] = '<\/pre>';

        $this->assertLines($expected, $actual);
    }

    /**
     * Test Standard Benchmark Output
     */
    public function testBenchmarkOutputAsHtmlCommentWithInstanceName()
    {
        Benchmark::getNewInstance('Name_Loop1')->start();
        $actual = Benchmark::getNewInstance('Name_Loop1')->stop();

        $expected[] = '';
        $expected[] = '    <!-- ======== \[ BENCHMARK DATA \] ========';
        $expected[] = '        Instance: Name_Loop1';
        $expected[] = '    Elapsed Time: 0ms';
        $expected[] = '    Elapsed Time: [0-9E.-]+ \(micro\)';
        $expected[] = '    Memory Usage: [0-9.-]+[B|Kb|Mb|Gb|Tb]+';
        $expected[] = '     Peak Memory: [0-9.-]+[B|Kb|Mb|Gb|Tb]+ \(or [0-9]+ bytes\)';
        $expected[] = '            Date: \d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d';
        $expected[] = '    ===================================== -->';

        $this->assertLines($expected, $actual);
    }

    /**
     * Test Standard Benchmark Output
     */
    public function testBenchmarkOutputDisplayAsHtmlWithInstanceName()
    {
        Benchmark::getNewInstance('Name_Loop2')->start();
        $actual = Benchmark::getNewInstance('Name_Loop2')->stop();

        $expected[] = '';
        $expected[] = '    <!-- ======== \[ BENCHMARK DATA \] ========';
        $expected[] = '        Instance: Name_Loop2';
        $expected[] = '    Elapsed Time: 0ms';
        $expected[] = '    Elapsed Time: [0-9E.-]+ \(micro\)';
        $expected[] = '    Memory Usage: [0-9.-]+[B|Kb|Mb|Gb|Tb]+';
        $expected[] = '     Peak Memory: [0-9.-]+[B|Kb|Mb|Gb|Tb]+ \(or [0-9]+ bytes\)';
        $expected[] = '            Date: \d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d';
        $expected[] = '    ===================================== -->';

        $this->assertLines($expected, $actual);
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
