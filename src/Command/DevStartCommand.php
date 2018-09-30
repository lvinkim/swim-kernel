<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 10:41 PM
 */

namespace Lvinkim\SwimKernel\Command;


use Lvinkim\SwimKernel\Component\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DevStartCommand extends Command
{

    protected function configure()
    {
        $this->setName("swim:dev:start")
            ->addArgument("port", InputArgument::OPTIONAL, "监听端口", "8080")
            ->setDescription("使用 php -S 启动应用");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $settings = $this->container["settings"];
        $projectDir = $settings["projectDir"];

        $port = $input->getArgument("port");

        $output->writeln("正在监听: http://0.0.0.0:{$port}");

        exec("/usr/bin/env php -S 0.0.0.0:{$port} -t {$projectDir}/public");
    }

}