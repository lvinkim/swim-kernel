<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/9/25
 * Time: 8:29 PM
 */

return (function () {

    date_default_timezone_set('Asia/Shanghai');

    (new Dotenv\Dotenv(dirname(__DIR__) . '/../../'))->load();

    "prod" === getenv("ENV") ? error_reporting(0) : null;

    return [
        "app" => "swim-kernel",
        "workerId" => "", // 由 worker 进程设置
        "env" => getenv("ENV"),
        "projectDir" => dirname(__DIR__),
        "serviceDir" => dirname(__DIR__) . "/Service",
        "middlewareDir" => dirname(__DIR__) . "/Middleware",
        "commandDir" => dirname(__DIR__) . "/Command",
        "namespace" => "Lvinkim\SwimKernel\Tests\App",
        "routes" => __DIR__ . "/routes.config.php",
        'logger' => [
            'directory' => dirname(__DIR__) . '/../../var',
        ],
    ];

})();
