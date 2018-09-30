<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 10:44 PM
 */

namespace Lvinkim\SwimKernel\Component;


use Slim\Container;

class Command extends \Symfony\Component\Console\Command\Command
{
    protected $container;

    public function __construct(Container $container)
    {
        parent::__construct();

        $this->container = $container;
    }

}