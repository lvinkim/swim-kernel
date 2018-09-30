<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 24/09/2018
 * Time: 3:26 PM
 */


use Lvinkim\SwimKernel\Tests\SwooleServer\SwooleCommand;

require dirname(__DIR__) . "/swoole-server/required.php";

$settings = require dirname(__DIR__) . "/config/kernel.config.php";

(new SwooleCommand($settings))->run();
