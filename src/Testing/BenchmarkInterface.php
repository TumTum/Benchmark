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
 * BenchmarkInterface is the interface implemented by Benchmark.
 *
 * @author Daryl Eisner <deisner@ucsd.edu>
 */
interface BenchmarkInterface
{
    /**
     * Constants.
     *
     * @var string CRLF               A carriage return line feed
     * @var string MYSQL_DATE_FORMAT  A MySQL date format
     */
    const CRLF              = "\r\n";
    const MYSQL_DATE_FORMAT = 'Y-m-d H:i:s';

    // --------------------------------------------------------------------------

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
     * @param  string $instanceName  A new object handle name.
     *
     * @return BenchmarkInterface
     */
    public static function getNewInstance($instanceName);

    // --------------------------------------------------------------------------

    /**
     * Start the benchmark clock.
     *
     * @return BenchmarkInterface
     */
    public function start();

    // --------------------------------------------------------------------------

    /**
     * Stop the benchmark clock.
     *
     * @throws \InvalidArgumentException on non boolean value for $display
     * @param  bool  $display   A raw memory usage
     *
     * @return bool|string  The display via print();
     */
    public function stop($display = false);

    // --------------------------------------------------------------------------

    /**
     * Initialization (Singleton Pattern).
     *
     * @static
     *
     * @return BenchmarkInterface
     *
     * @api
     */
    public static function init();

    // --------------------------------------------------------------------------

    /**
     * Returns instance count.
     *
     * @static
     *
     * @return integer
     *
     * @api
     */
    public static function getInstanceCount();
}
