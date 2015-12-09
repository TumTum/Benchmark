<?php

/*
 * This file is part of the UCSDMath package.
 *
 * (c) UCSD Mathematics | Math Computing Support <mathhelp@math.ucsd.edu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UCSDMath\Testing;

/**
 * Benchmark provides an abstract base class implementation of {@link BenchmarkInterface}.
 * Primarily, this services the fundamental implementations for testing PHP.
 *
 * Method list:
 *
 * @method BenchmarkInterface __construct();
 * @method void               __destruct();
 * @method start();
 * @method getStats();
 * @method stop($display = false);
 * @method getNewInstance($instanceName);
 * @method getTime($raw = false, $format = null);
 * @method getPeakMemory($raw = false, $format = null);
 * @method getMemoryUsage($raw = false, $format = null);
 * @method readableMemorySize($size = null, $format = null);
 * @method readableElapseTime($microtime = null, $format = null, $round = 3);
 *
 * @author Daryl Eisner <deisner@ucsd.edu>
 */
final class Benchmark implements BenchmarkInterface
{
    /**
     * Constants.
     *
     * @var string VERSION  A version number
     *
     * @api
     */
    const VERSION = '1.5.0';

    // --------------------------------------------------------------------------

    /**
     * Properties.
     *
     * @var    float              $start        A start unix timestamp in microseconds
     * @var    float              $stop         A stop unix timestamp in microseconds
     * @var    integer            $memoryUse    A memory allocated from system (in real size)
     * @var    bool               $display      A page display, comments display [default false]
     * @static BenchmarkInterface $instance     A BenchmarkInterface instance
     * @static array              $instances    A Benchmark array
     * @static integer            $objectCount  A BenchmarkInterface instance count
     */
    private $start              = null;
    private $stop               = null;
    private $memoryUse          = null;
    private $display            = false;
    private static $instance    = null;
    private static $instances   = array();
    private static $objectCount = 0;

    // --------------------------------------------------------------------------

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

    // --------------------------------------------------------------------------

    /**
     * Destructor.
     *
     * @api
     */
    public function __destruct()
    {
        self::$objectCount--;
    }

    // --------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        $this->start = microtime(true);

        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get statistical data.
     *
     * Display benchmark stats
     *
     * @return string
     */
    private function getStats()
    {
        $dataBoard  = (true === $this->display) ?
            (self::CRLF . '<pre id="bench">' . self::CRLF) : (self::CRLF.'    <!--');

        $dataBoard .=

            ' ======== [ BENCHMARK DATA ] ======== ' . self::CRLF .
            '    Elapsed Time: ' . $this->getTime() . self::CRLF .
            '    Elapsed Time: ' . $this->getTime(true) . ' (micro)' . self::CRLF .
            '    Memory Usage: ' . $this->getMemoryUsage() . self::CRLF .
            '     Peak Memory: ' . $this->getPeakMemory(false, '%.3f%s') .
            ' (or '.$this->getPeakMemory(true).' bytes)'.self::CRLF .
            '            Date: '. date(self::MYSQL_DATE_FORMAT). self::CRLF .
            '    ===================================== ';

        $dataBoard .= (true === $this->display) ?
            (self::CRLF . '</pre>') : ('-->');

        return $dataBoard;
    }

    // --------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function stop($display = false)
    {
        $this->display = !empty($display) ? true : false;
        $this->stop = microtime(true);
        $this->memoryUse = memory_get_usage(true);

        return $this->getStats();
    }

    // --------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public static function getNewInstance($instanceName)
    {
        if (!array_key_exists($instanceName, self::$instances)) {
            self::$instances[$instanceName] = new static();
        }

        return self::$instances[$instanceName];
    }

    // --------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public static function init()
    {
        if (null === static::$instance) {
            static::$instance = new static;
            static::$objectCount++;
        }

        return static::$instance;
    }

    // --------------------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public static function getInstanceCount()
    {
        return (int) static::$objectCount;
    }

    // --------------------------------------------------------------------------

    /**
     * Get elapse time.
     *
     * @use    Benchmark::getStats();
     * @throws \InvalidArgumentException on non boolean value for $raw
     * @param  bool    $raw     A raw memory usage
     * @param  string  $format  A decimal format
     *
     * @return string
     */
    private function getTime($raw = false, $format = null)
    {
        $elapsed = $this->stop - $this->start;

        return $raw ? $elapsed : self::readableElapseTime($elapsed, $format);
    }

    // --------------------------------------------------------------------------

    /**
     * Get peak memory usage.
     *
     * @use    Benchmark::getStats();
     * @throws \InvalidArgumentException on non boolean value for $raw
     * @param  bool    $raw     A raw memory usage
     * @param  string  $format  A decimal format
     *
     * @return string
     */
    private function getPeakMemory($raw = false, $format = null)
    {
        $memory = memory_get_peak_usage(true);

        return $raw ? $memory : self::readableMemorySize($memory, $format);
    }

    // --------------------------------------------------------------------------

    /**
     * Get a general memory usage.
     *
     * @use    Benchmark::getStats();
     * @throws \InvalidArgumentException on non boolean value for $raw
     * @param  bool    $raw     A raw memory usage
     * @param  string  $format  A decimal format
     *
     * @return string
     */
    private function getMemoryUsage($raw = false, $format = null)
    {
        /**
         * Check Arguments
         */
        if (!is_bool($raw)) {
            throw new \Exception(sprintf(
                'Required in "%s". Parameter %s must exist and be of type boolean. %s',
                'Benchmark::getMemoryUsage()',
                '{$raw}',
                '[bench-B202]'
            ));
        }

        return $raw ? $this->memoryUse : self::readableMemorySize($this->memoryUse, $format);
    }

    // --------------------------------------------------------------------------

    /**
     * Create a readable memory size.
     *
     * @param  bool    $size    A raw memory size
     * @param  string  $format  A decimal format
     *
     * @return string
     */
    private static function readableMemorySize($size = null, $format = null)
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

        return sprintf($format, round($size, $round), $units[$i]);
    }

    // --------------------------------------------------------------------------

    /**
     * Create a readable elapse time.
     *
     * @param  integer $microtime  A unix timestamp in microseconds
     * @param  string  $format     A decimal format
     *
     * @return string
     */
    private static function readableElapseTime($microtime = null, $format = null)
    {
        /* A decimal point rounding */
        $round = 3;

        $format = is_null($format)
            ? '%.3f%s'
            : $format;

        if ($microtime >= 1) {
            $unit = 's';
            $time = round($microtime, $round);

        } else {
            $unit = 'ms';
            $time = round($microtime * 1000);
            $format = preg_replace('/(%.[\d]+f)/', '%d', $format);
        }

        return sprintf($format, $time, $unit);
    }

    // --------------------------------------------------------------------------

    /**
     * Method implementations inserted.
     *
     * The notation below illustrates visibility: (+) @api, (-) protected or private.
     *
     * @method all();
     * @method init();
     * @method get($key);
     * @method has($key);
     * @method version();
     * @method getClassName();
     * @method getConst($key);
     * @method set($key, $value);
     * @method isString($str);
     * @method getInstanceCount();
     * @method getClassInterfaces();
     * @method __call($callback, $parameters);
     * @method getProperty($name, $key = null);
     * @method doesFunctionExist($functionName);
     * @method isStringKey($str, array $keys);
     * @method throwExceptionError(array $error);
     * @method setProperty($name, $value, $key = null);
     * @method throwInvalidArgumentExceptionError(array $error);
     */
    use ServiceFunctions;
}
