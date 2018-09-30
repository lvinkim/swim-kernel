<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 5:47 PM
 */

namespace Lvinkim\SwimKernel\Processor;


use Lvinkim\SwimKernel\Component\ActionResponse;
use Slim\App;
use Slim\Container;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Table;

class RequestProcessor
{
    private $container;

    /**
     * RequestProcessor constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param Table $table
     * @return ActionResponse
     */
    public function process(Request $request, Response $response, Table $table): ActionResponse
    {
        $this->container[Request::class] = $request;
        $this->container[Response::class] = $response;
        $this->container[Table::class] = $table;

        $requestData = $this->resolveRequestData($request);
        $userData = $this->resolveUserData($request);

        $environment = \Slim\Http\Environment::mock($userData);
        $slimRequest = \Slim\Http\Request::createFromEnvironment($environment);
        if ($requestData) {
            $slimRequest = $slimRequest->withParsedBody($requestData);
        }

        $slimResponse = new \Slim\Http\Response();
        try {
            /** @var App $app */
            $app = $this->container->raw(App::class);
            $slimResponse = $app->process($slimRequest, $slimResponse);
            $bodyContents = (string)$slimResponse->getBody();
            $contentType = $slimResponse->getHeaderLine("Content-Type");
        } catch (\Exception $exception) {
            $bodyContents = json_encode(["error" => $exception->getMessage()]);
            $contentType = "";
        }
        $contentType = $contentType ?: "application/json;charset=utf-8";

        $actionResponse = new ActionResponse($bodyContents);
        $actionResponse->setContentType($contentType);
        return $actionResponse;
    }

    /**
     * @param Request $request
     * @return array|mixed
     */
    private function resolveRequestData(Request $request)
    {
        $contentType = $request->header['content-type'] ?? '';
        if ('application/json' == $contentType) {
            $requestData = json_decode($request->rawContent(), true);
        } else {
            $get = $request->get ?? [];
            $post = $request->post ?? [];
            $requestData = array_merge($get, $post);
        }

        return $requestData;
    }

    private function resolveUserData(Request $request)
    {
        $userData = [];
        if (isset($request->header)) {
            foreach ($request->header as $key => $value) {
                $userData["HTTP_" . strtoupper($key)] = $value;
            }
        }
        if (isset($request->server)) {
            foreach ($request->server as $key => $value) {
                $userData[strtoupper($key)] = $value;
            }
        }
        return $userData;
    }

}