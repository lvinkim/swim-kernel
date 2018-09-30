<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 10:53 PM
 */

use Symfony\Component\Console\Input\ArrayInput;

require dirname(__DIR__) . "/../../vendor/autoload.php";

$settings = require dirname(__DIR__) . "/config/settings.config.php";

$kernel = new \Lvinkim\SwimKernel\Kernel($settings);

$console = new \Lvinkim\SwimKernel\Console($kernel);

$commandInput = new ArrayInput(['command' => "slim:run"]);

$console->run($commandInput);