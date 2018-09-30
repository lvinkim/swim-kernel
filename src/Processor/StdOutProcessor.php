<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 7:41 PM
 */

namespace Lvinkim\SwimKernel\Processor;


class StdOutProcessor
{
    static public $workerId = 0;

    public static function writeln($string)
    {
        if (self::$workerId === 0) {
            echo "- worker -: {$string}" . PHP_EOL;
        }
    }
}