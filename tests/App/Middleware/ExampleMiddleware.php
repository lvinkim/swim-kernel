<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 8:57 PM
 */

namespace Lvinkim\SwimKernel\Tests\App\Middleware;


use Lvinkim\SwimKernel\Component\MiddlewareInterface;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class ExampleMiddleware implements MiddlewareInterface
{

    /**
     * $container 包含了所有已实例化的 Service 对象和 Action 对象
     * ActionInterface constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $next
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        return $next($request, $response);
    }
}