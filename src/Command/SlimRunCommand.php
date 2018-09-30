<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 10:54 PM
 */

namespace Lvinkim\SwimKernel\Command;


use Lvinkim\SwimKernel\Component\Command;
use Slim\App;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SlimRunCommand extends Command
{

    protected function configure()
    {
        $this->setName("slim:run")
            ->setDescription("执行 slim 框架");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var App $app */
        $app = $this->container->raw(App::class);
        $app->run();
    }

}