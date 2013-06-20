<?php

namespace cbednarski\souschef\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PromoteEnvironment extends Command
{
    protected function configure()
    {
        $this->setName('promote');
        $this->setDescription('Promoting one environment in a blue-green pair will copy all of the configuration to the matching environment pair');

        $this->addArgument('environment', InputArgument::REQUIRED, 'The environment to promote');
    }
}