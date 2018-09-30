<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 4:07 PM
 */

namespace Lvinkim\SwimKernel\Processor;


use Lvinkim\SwimKernel\Middleware\AccessMiddleware;
use Lvinkim\SwimKernel\Service\SwimKernelLogger;
use Lvinkim\SwimKernel\Handler\ErrorHandler;
use Lvinkim\SwimKernel\Handler\NotAllowedHandler;
use Lvinkim\SwimKernel\Handler\NotFoundHandler;
use Lvinkim\SwimKernel\Handler\PhpErrorHandler;
use Lvinkim\SwimKernel\Utility\DirectoryScanner;
use Slim\App;
use Slim\Container;

class LoadProcessor
{
    /**
     * @param $settings
     * @return \Psr\Container\ContainerInterface
     */
    public function load($settings)
    {
        StdOutProcessor::$workerId = $settings["workerId"] ?? 0;

        $app = new App(["settings" => $settings]);
        $container = $app->getContainer();
        $container[App::class] = $app;

        $dependencies = $settings["dependencies"] ?? "";
        $middleware = $settings["middleware"] ?? "";
        $routes = $settings["routes"] ?? "";

        $requires = [$dependencies, $middleware, $routes];

        foreach ($requires as $require) {
            if (is_file($require)) {
                require $require . "";
            }
        }

        $kernelServices = $this->getKernelService();
        foreach ($kernelServices as $id => $kernelService) {
            StdOutProcessor::writeln("正在注册内核服务 : {$kernelService}");
            $container[$id] = function (Container $c) use ($kernelService) {
                return new $kernelService($c);
            };
        }

        $serviceClasses = $this->getAllServiceClasses($settings);
        foreach ($serviceClasses as $idx => $serviceClass) {
            StdOutProcessor::writeln("正在注册应用服务 {$idx}: {$serviceClass}");
            $container[$serviceClass] = function (Container $c) use ($serviceClass) {
                return new $serviceClass($c);
            };
        }

        $middlewareClasses = $this->getAllMiddlewareClasses($settings);
        foreach ($middlewareClasses as $idx => $middlewareClass) {
            StdOutProcessor::writeln("正在注册中间件 {$idx}: {$middlewareClass}");
            $app->add(new $middlewareClass($container));
        }

        return $container;
    }


    /**
     * @return array
     */
    private function getKernelService()
    {
        return [
            SwimKernelLogger::class => SwimKernelLogger::class,
            "errorHandler" => ErrorHandler::class,
            "phpErrorHandler" => PhpErrorHandler::class,
            "notAllowedHandler" => NotAllowedHandler::class,
            "notFoundHandler" => NotFoundHandler::class,
        ];
    }

    /**
     * @param $settings
     * @return array
     */
    private function getAllServiceClasses($settings)
    {
        $serviceDir = $settings["serviceDir"];
        $namespace = $settings["namespace"] . "\Service";
        $appServices = DirectoryScanner::getClassesRecursion($serviceDir, $namespace);

        return $appServices;
    }

    /**
     * @param $settings
     * @return array
     */
    private function getAllMiddlewareClasses($settings)
    {
        $kernelMiddleware = [
            AccessMiddleware::class,
        ];

        $serviceDir = $settings["middlewareDir"];
        $namespace = $settings["namespace"] . "\Middleware";
        $appMiddleware = DirectoryScanner::getClassesRecursion($serviceDir, $namespace);

        return array_merge($kernelMiddleware, $appMiddleware);
    }


}