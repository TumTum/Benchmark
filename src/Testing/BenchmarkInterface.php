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
 * BenchmarkInterface is the interface implemented by all Benchmark classes.
 *
 * Method list: (+) @api.
 *
 * @author Daryl Eisner <deisner@ucsd.edu>
 *
 * @api
 */
interface BenchmarkInterface
{
    /**
     * Constants.
     *
     * @var string MYSQL_DATE_FORMAT The MySQL date format
     * @var string CRLF              The carriage return line feed
     */
    const MYSQL_DATE_FORMAT = 'Y-m-d H:i:s';
    const CRLF              = "\r\n";

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
     * @param string $instanceName The new object handle name.
     *
     * @return BenchmarkInterface The current instance
     */
    public static function getNewInstance(string $instanceName): BenchmarkInterface;

    //--------------------------------------------------------------------------

    /**
     * Start the benchmark clock.
     *
     * @return BenchmarkInterface The current instance
     */
    public function start(): BenchmarkInterface;

    //--------------------------------------------------------------------------

    /**
     * Stop the benchmark clock.
     *
     * @param bool $display The raw memory usage
     *
     * @return string The display via print();
     */
    public function stop(bool $display = false): string;

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
    public static function init(): BenchmarkInterface;

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
    public static function getInstanceCount(): int;

    //--------------------------------------------------------------------------
}
