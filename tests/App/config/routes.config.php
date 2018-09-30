<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 24/09/2018
 * Time: 10:41 PM
 */

use Lvinkim\SwimKernel\Tests\App\Action\IndexAction;
use Lvinkim\SwimKernel\Tests\App\Action\UpdateAction;
use Slim\App;

/** @var \Slim\Container $container */

/**
 * @param \Slim\Container $container
 */
(function (\Slim\Container $container) {

    /** @var App $app */
    $app = $container->raw(App::class);

    $app->any("/", IndexAction::class);
    $app->any("/update", UpdateAction::class);

})($container);
