<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 4:02 PM
 */

namespace Lvinkim\SwimKernel\Component;


use Psr\Container\ContainerInterface;

interface ServiceInterface
{
    /**
     * $container 包含了所有已实例化的 Service 对象和 Action 对象
     * ActionInterface constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container);
}