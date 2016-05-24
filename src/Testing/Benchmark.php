<?php

/*
 * This file is part of the UCSDMath package.
 *
 * Copyright 2016 UCSD Mathematics | Math Computing Support <mathhelp@math.ucsd.edu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace UCSDMath\Testing;

/**
 * Benchmark is the default implementation of {@link BenchmarkInterface} which
 * provides routine Testing methods that are commonly used in the framework.
 *
 * {@link Benchmark} is basically a script performance tester and is considered a
 * base or final class.
 *
 * Method list: (+) @api, (-) protected or private visibility.
 *
 * (+) BenchmarkInterface __construct();
 * (+) void __destruct();
 * (-) string getStats();
 * (+) BenchmarkInterface start();
 * (+) string stop($display = false);
 * (-) string getTime($raw = false, $format = null);
 * (+) BenchmarkInterface getNewInstance($instanceName);
 * (-) int getMemoryUsage($raw = false, $format = null);
 * (-) string readableMemorySize(int $size = null, $format = null);
 * (-) integer|string getPeakMemory($raw = false, $format = null);
 * (-) float readableElapseTime($microtime = null, $format = null, $round = 3);
 *
 * @author Daryl Eisner <deisner@ucsd.edu>
 *
 * @api
 */
final class Benchmark implements BenchmarkInterface
{
    /**
     * Constants.
     *
     * @var string VERSION A version number
     *
     * @api
     */
    const VERSION = '1.7.0';

    //--------------------------------------------------------------------------

    /**
     * Properties.
     *
     * @var    float              $start        A start unix timestamp in microseconds
     * @var    float              $stop         A stop unix timestamp in microseconds
     * @var    int                $memoryUse    A memory allocated from system (in real size)
     * @var    bool               $display      A page display, comments display [default false]
     * @static BenchmarkInterface $instance     A BenchmarkInterface
     * @static array              $instances    A Benchmark array
     * @static int                $objectCount  A BenchmarkInterface count
     */
    private $start              = null;
    private $stop               = null;
    private $memoryUse          = 0;
    private $display            = false;
    private static $instance    = null;
    private static $instances   = array();
    private static $objectCount = 0;

    //--------------------------------------------------------------------------

    /**
     * Constructor.
     *
     * @api
     */
    public function __construct()
    {
        self::$instance = $this;
        self::$objectCount++;
    }

    //--------------------------------------------------------------------------

    /**
     * Destructor.
     *
     * @api
     */
    public function __destruct()
    {
        self::$objectCount--;
    }

    //--------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function start(): BenchmarkInterface
    {
        $this->start = microtime(true);

        return $this;
    }

    //--------------------------------------------------------------------------

    /**
     * Get statistical data.
     *
     * Display benchmark stats
     *
     * @return string
     */
    private function getStats(): string
    {
        $dataBoard  = (true === $this->display) ?
            (self::CRLF . '<pre id="bench">' . self::CRLF) : (self::CRLF . '    <!--');

        $dataBoard .=

            ' ======== [ BENCHMARK DATA ] ======== ' . self::CRLF .
            '    Elapsed Time: ' . $this->getTime() . self::CRLF .
            '    Elapsed Time: ' . $this->getTime(true) . ' (micro)' . self::CRLF .
            '    Memory Usage: ' . $this->getMemoryUsage() . self::CRLF .
            '     Peak Memory: ' . $this->getPeakMemory(false, '%.3f%s') .
            ' (or ' . $this->getPeakMemory(true) . ' bytes)' . self::CRLF .
            '            Date: ' . date(self::MYSQL_DATE_FORMAT) . self::CRLF .
            '    ===================================== ';

        $dataBoard .= (true === $this->display) ? (self::CRLF . '</pre>') : ('-->');

        return (string) $dataBoard;
    }

    //--------------------------------------------------------------------------

    /**
     * Stop the benchmark clock.
     *
     * @param bool  $display  A raw memory usage
     *
     * @return string  A display via print();
     */
    public function stop(bool $display = false): string
    {
        $this->display = !empty($display) ? true : false;
        $this->stop = microtime(true);
        $this->memoryUse = memory_get_usage(true);

        return (string) $this->getStats();
    }

    //--------------------------------------------------------------------------

    /**
     * Specify 1, 2, or 3 Benchmark objects and recall by name.
     *
     * Multiton pattern implementation.
     * This is considered to be an anti-pattern. For better testability
     * and maintainability use dependency injection.
     *
     * @purpose  To have only a list of named instances that are used,
     *           like a singleton but with n instances.
     *
     * @static
     *
     * @param string $instanceName  A new object handle name.
     *
     * @return BenchmarkInterface The current instance
     */
    public static function getNewInstance(string $instanceName): BenchmarkInterface
    {
        if (!array_key_exists($instanceName, self::$instances)) {
            self::$instances[$instanceName] = new self();
        }

        return self::$instances[$instanceName];
    }

    //--------------------------------------------------------------------------

    /**
     * Initialization (Singleton Pattern).
     *
     * @static
     *
     * @return BenchmarkInterface The current instance
     *
     * @api
     */
    public static function init(): BenchmarkInterface
    {
        if (null === self::$instance) {
            self::$instance = new self();
            self::$objectCount++;
        }

        return self::$instance;
    }

    //--------------------------------------------------------------------------

    /**
     * Returns instance count.
     *
     * @static
     *
     * @return int
     *
     * @api
     */
    public static function getInstanceCount(): int
    {
        return (int) self::$objectCount;
    }

    //--------------------------------------------------------------------------

    /**
     * Get elapse time.
     *
     * @use    Benchmark::getStats();
     * @throws \InvalidArgumentException on non boolean value for $raw
     * @param bool    $raw     A raw memory usage
     * @param string  $format  A decimal format
     *
     * @return string
     */
    private function getTime($raw = false, $format = null): string
    {
        $elapsed = $this->stop - $this->start;

        return $raw ? (string) $elapsed : (string) self::readableElapseTime($elapsed, $format);
    }

    //--------------------------------------------------------------------------

    /**
     * Get peak memory usage.
     *
     * @use    Benchmark::getStats();
     *
     * @param bool    $raw     A raw memory usage
     * @param string  $format  A decimal format
     *
     * @return string
     */
    private function getPeakMemory(bool $raw = false, string $format = null): string
    {
        $memory = memory_get_peak_usage(true);

        return $raw ? (string) $memory : (string) self::readableMemorySize($memory, $format);
    }

    //--------------------------------------------------------------------------

    /**
     * Get a general memory usage.
     *
     * @use    Benchmark::getStats();
     * @throws \Exception on non boolean value for $raw
     *
     * @param bool    $raw     A raw memory usage
     * @param string  $format  A decimal format
     *
     * @return string
     */
    private function getMemoryUsage(bool $raw = false, string $format = null): string
    {
        /**
         * Check Arguments.
         */
        if (!is_bool($raw)) {
            throw new \Exception(sprintf(
                'Required in "%s". Parameter %s must exist and be of type boolean. %s',
                'Benchmark::getMemoryUsage()',
                '{$raw}',
                '[bench-B202]'
            ));
        }

        return $raw ? (string) $this->memoryUse : (string) self::readableMemorySize($this->memoryUse, $format);
    }

    //--------------------------------------------------------------------------

    /**
     * Create a readable memory size.
     *
     * @param int    $size    A raw memory size
     * @param string $format  A decimal format
     *
     * @return string
     */
    private static function readableMemorySize(int $size = null, string $format = null): string
    {
        /* A decimal point rounding */
        $round = 3;
        $mod = 1024;
        $units = explode(':', 'B:Kb:Mb:Gb:Tb');

        $format = is_null($format)
            ? '%.3f%s'
            : $format;

        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }

        if (0 === $i) {
            $format = preg_replace('/(%.[\d]+f)/', '%d', $format);
        }

        return (string) sprintf($format, round($size, $round), $units[$i]);
    }

    //--------------------------------------------------------------------------

    /**
     * Create a readable elapse time.
     *
     * @param float  $microtime  A unix timestamp in microseconds
     * @param string $format     A decimal format
     *
     * @return string
     */
    private static function readableElapseTime(float $microtime = null, string $format = null): string
    {
        /* A decimal point rounding */
        $round = 3;

        $format = is_null($format) ? '%.3f%s' : $format;

        if ($microtime >= 1) {
            $unit = 's';
            $time = round($microtime, $round);

        } else {
            $unit = 'ms';
            $time = round($microtime * 1000);
            $format = preg_replace('/(%.[\d]+f)/', '%d', $format);
        }

        return (string) sprintf($format, $time, $unit);
    }

    //--------------------------------------------------------------------------
}
