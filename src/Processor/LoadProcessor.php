<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 4:07 PM
 */

namespace Lvinkim\SwimKernel\Processor;


use HaydenPierce\ClassFinder\ClassFinder;
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

        $serviceClasses = $this->getAllServiceClasses($settings);
        foreach ($serviceClasses as $idx => $serviceClass) {
            StdOutProcessor::writeln("正在注册服务 {$idx}: {$serviceClass}");
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

    private function getAllMiddlewareClasses($settings)
    {
        $kernelMiddleware = [
            AccessMiddleware::class,
        ];

        $serviceDir = $settings["middlewareDir"];
        $namespace = $settings["namespace"] . "\Middleware";
        $appMiddleware = $this->getClassesRecursion($serviceDir, $namespace);

        return array_merge($kernelMiddleware, $appMiddleware);
    }

    /**
     * @param $settings
     * @return array
     */
    private function getAllServiceClasses($settings)
    {
        $kernelServices = [
            SwimKernelLogger::class,
            ErrorHandler::class,
            PhpErrorHandler::class,
            NotAllowedHandler::class,
            NotFoundHandler::class,
        ];

        $serviceDir = $settings["serviceDir"];
        $namespace = $settings["namespace"] . "\Service";
        $appServices = $this->getClassesRecursion($serviceDir, $namespace);

        return array_merge($kernelServices, $appServices);
    }

    /**
     * @param $directory
     * @param $namespace
     * @return array
     */
    private function getClassesRecursion($directory, $namespace)
    {
        try {
            $classes = ClassFinder::getClassesInNamespace($namespace);

            $subDirectories = DirectoryScanner::scanChildNamespaces($directory);
            foreach ($subDirectories as $subDirectory) {
                $subClasses = ClassFinder::getClassesInNamespace($namespace . $subDirectory);
                $classes = array_merge($classes, $subClasses);
            }
        } catch (\Exception $exception) {
            $classes = [];
        }

        return $classes;
    }

}