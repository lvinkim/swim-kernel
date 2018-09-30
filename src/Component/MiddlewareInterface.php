<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 8:59 PM
 */

namespace Lvinkim\SwimKernel\Component;


use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

interface MiddlewareInterface
{
    /**
     * $container 包含了所有已实例化的 Service 对象和 Action 对象
     * Container constructor.
     * @param Container $container
     */
    public function __construct(Container $container);

    /**
     * @param Request $request
     * @param Response $response
     * @param $next
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $next);
}