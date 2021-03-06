<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 24/09/2018
 * Time: 6:22 PM
 */

require dirname(__DIR__) . "/../../vendor/autoload.php";

$settings = require dirname(__DIR__) . "/config/settings.config.php";

$kernel = new \Lvinkim\SwimKernel\Kernel($settings);

$console = new \Lvinkim\SwimKernel\Console($kernel);

$console->run();
