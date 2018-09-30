<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 30/09/2018
 * Time: 10:09 PM
 */

namespace Lvinkim\SwimKernel;


use Lvinkim\SwimKernel\Command\DevStartCommand;
use Lvinkim\SwimKernel\Command\SlimRunCommand;
use Lvinkim\SwimKernel\Utility\DirectoryScanner;
use Slim\Container;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Console extends Application
{
    /** @var Container */
    private $container;

    public function __construct(Kernel $kernel)
    {
        parent::__construct("Swim", Kernel::VERSION);

        $kernel->dispatchWorkerStart(-1);
        $this->container = $kernel->getContainer();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Throwable
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->registerCommands();

        return parent::doRun($input, $output);
    }

    private function registerCommands()
    {
        $commandClasses = $this->getAllCommandClasses();
        foreach ($commandClasses as $commandClass) {
            $this->add(new $commandClass($this->container));
        }
    }

    private function getAllCommandClasses()
    {
        $settings = $this->container["settings"];

        $kernelCommands = [
            DevStartCommand::class,
            SlimRunCommand::class,
        ];

        $serviceDir = $settings["commandDir"];
        $namespace = $settings["namespace"] . "\Command";
        $appCommands = DirectoryScanner::getClassesRecursion($serviceDir, $namespace);

        return array_merge($kernelCommands, $appCommands);

    }
}