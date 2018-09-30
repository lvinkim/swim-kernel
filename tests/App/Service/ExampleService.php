<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 7:34 PM
 */

namespace Lvinkim\SwimKernel\Tests\App\Service;


use Lvinkim\SwimKernel\Component\ServiceInterface;
use Psr\Container\ContainerInterface;

class ExampleService implements ServiceInterface
{
    private $app;

    /**
     * $container 包含了所有已实例化的 Service 对象和 Action 对象
     * ActionInterface constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $settings = $container["settings"];
        $this->app = $settings["app"];
    }

    /**
     * @return mixed
     */
    public function getApp()
    {
        return $this->app;
    }


}