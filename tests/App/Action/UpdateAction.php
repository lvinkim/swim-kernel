<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 9:04 PM
 */

namespace Lvinkim\SwimKernel\Tests\App\Action;


use Lvinkim\SwimKernel\Component\ActionInterface;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Swoole\Table;

class UpdateAction implements ActionInterface
{

    private $settings;
    /** @var \Swoole\Http\Request */
    private $swooleRequest;
    /** @var \Swoole\Http\Response */
    private $swooleResponse;
    /** @var Table */
    private $swooleTable;

    /**
     * $container 包含了所有已实例化的 Service 对象和 Action 对象
     * Container constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->settings = $container["settings"];
        $this->swooleRequest = $container[\Swoole\Http\Request::class];
        $this->swooleResponse = $container[\Swoole\Http\Response::class];
        $this->swooleTable = $container[Table::class];
    }

    /**
     * Action 入库函数
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        $this->swooleTable->set("app", ["json" => json_encode(["num" => mt_rand(100, 999)])]);

        return $response->withJson([
            "app" => $this->settings["app"] ?? "",
            "worker" => $this->settings["workerId"] ?? 0,
            "fd" => $this->swooleRequest->fd ?? 0,
            "json" => $this->swooleTable->get("app"),
        ]);
    }
}