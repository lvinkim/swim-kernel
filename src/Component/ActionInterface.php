<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 4:01 PM
 */

namespace Lvinkim\SwimKernel\Component;


use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;


interface ActionInterface
{
    /**
     * $container 包含了所有已实例化的 Service 对象和 Action 对象
     * Container constructor.
     * @param Container $container
     */
    public function __construct(Container $container);

    /**
     * Action 入库函数
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, array $args);
}