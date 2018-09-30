<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/3
 * Time: 7:29 PM
 */

namespace Lvinkim\SwimKernel\Handler;


use Lvinkim\SwimKernel\Service\SwimKernelLogger;
use Lvinkim\SwimKernel\Component\ServiceInterface;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class NotAllowedHandler implements ServiceInterface
{
    /** @var SwimKernelLogger */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container[SwimKernelLogger::class];
    }

    public function __invoke(Request $request, Response $response, array $methods)
    {
        $this->logger->log('not-allowed', ['uri' => $request->getUri(), 'methods' => $methods], 'error');

        return $response->withStatus(405)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write(json_encode([
                'success' => false,
                'message' => '405 Not Allowed',
                'data' => $methods
            ]));
    }
}