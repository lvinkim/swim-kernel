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

class PhpErrorHandler implements ServiceInterface
{
    /** @var SwimKernelLogger */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container[SwimKernelLogger::class];
    }

    public function __invoke(Request $request, Response $response, \Throwable $throwable)
    {
        $this->logger->log('php-error', ['error' => $throwable->getMessage()], 'error');

        return $response->withStatus(500)
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->write(json_encode([
                'success' => false,
                'message' => '500 PHP Error',
                'data' => [
                    'error' => $throwable->getMessage()
                ]
            ]));
    }
}