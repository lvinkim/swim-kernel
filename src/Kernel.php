<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 24/09/2018
 * Time: 3:24 PM
 */

namespace Lvinkim\SwimKernel;

use Lvinkim\SwimKernel\Component\KernelInterface;
use Lvinkim\SwimKernel\Processor\LoadProcessor;
use Lvinkim\SwimKernel\Processor\RequestProcessor;
use Slim\Container;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Table;

class Kernel implements KernelInterface
{
    const VERSION = "v0.1.0";

    private $settings;

    /** @var Container */
    private $container;

    /**
     * KernelInterface constructor.
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * 在 onWorkerStart 回调事件中的处理函数
     * @param int $workerId
     * @return mixed|void
     */
    public function dispatchWorkerStart(int $workerId)
    {
        $this->settings["workerId"] = $workerId;

        $loadProcessor = new LoadProcessor();
        $this->container = $loadProcessor->load($this->settings);
    }

    /**
     * 在 onRequest 回调事件中的处理函数
     * @param Request $request
     * @param Response $response
     * @param Table $table
     * @return mixed|void
     */
    public function dispatchRequest(Request $request, Response $response, Table $table)
    {
        $requestProcessor = new RequestProcessor($this->container);
        $actionResponse = $requestProcessor->process($request, $response, $table);

        if (!$actionResponse->isSent()) {
            $response->header("Content-Type", $actionResponse->getContentType());

            $contentBlocks = str_split($actionResponse->getBody(), 2046 * 1024);
            foreach ($contentBlocks as $block) {
                $response->write($block);
            }

            $response->end();
        }
    }

    /**
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }
}