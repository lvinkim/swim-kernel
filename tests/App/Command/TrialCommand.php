<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 11:14 PM
 */

namespace Lvinkim\SwimKernel\Tests\App\Command;


use Lvinkim\SwimKernel\Component\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TrialCommand extends Command
{
    protected function configure()
    {
        $this->setName("app:trial")
            ->setDescription("测试脚本");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("[" . date("Y-m-d H:i:s") . "] " . $this->getName() . " 开始");

        $output->writeln("[" . date("Y-m-d H:i:s") . "] " . $this->getName() . " 结束");
    }

}