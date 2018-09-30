<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/7/24
 * Time: 2:11 PM
 */

namespace Lvinkim\SwimKernel\Middleware;


use Lvinkim\SwimKernel\Component\MiddlewareInterface;
use Lvinkim\SwimKernel\Service\SwimKernelLogger;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;


class AccessMiddleware implements MiddlewareInterface
{
    /** @var SwimKernelLogger */
    private $logger;

    /** @var array */
    private $marker = [];

    private $settings;

    /**
     * AccessMiddleware constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->logger = $container[SwimKernelLogger::class];
        $this->settings = $container["settings"];
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        $content = [
            "worker" => $this->settings["workerId"] ?? 0,
            "ip" => $request->getServerParam('REMOTE_ADDR'),
            "method" => $request->getMethod(),
            "path" => $request->getUri()->getPath(),
            "params" => $request->getParams(),
        ];
        $this->logger->log('access', $content, 'access');

        $this->mark('access-cost-begin');

        $nextResponse = $next($request, $response);

        $this->mark('access-cost-end');

        try {

            /** @var  $request Request */
            $elapsedTime = $this->elapsedTime('access-cost-begin', 'access-cost-end');;
            $content['elapsed'] = $elapsedTime;

            $this->logger->log('cost', $content, 'access');

        } catch (\Error $e) {

        } catch (\Exception $e) {

        }

        return $nextResponse;
    }

    private function mark($name)
    {
        $this->marker[$name] = microtime();
    }

    private function elapsedTime($point1 = '', $point2 = '', $decimals = 4)
    {
        if ($point1 == '') {
            return '{elapsed_time}';
        }

        if (!isset($this->marker[$point1])) {
            return '';
        }

        if (!isset($this->marker[$point2])) {
            $this->marker[$point2] = microtime();
        }

        list($sm, $ss) = explode(' ', $this->marker[$point1]);
        list($em, $es) = explode(' ', $this->marker[$point2]);

        $elapsedTime = number_format(($em + $es) - ($sm + $ss), $decimals);
        $elapsedTime = round(floatval($elapsedTime), 4);
        return $elapsedTime;
    }

}