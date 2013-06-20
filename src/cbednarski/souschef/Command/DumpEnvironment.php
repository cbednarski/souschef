<?php

namespace cbednarski\souschef\Command;

use cbednarski\souschef\CliWrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DumpEnvironment extends Command
{
    protected function configure()
    {
        $this->setName('dump');
        $this->setDescription('Dump an environment file to the current directory');

        $this->addArgument('environment', InputArgument::REQUIRED, 'environment to dump');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $env = $input->getArgument("environment");
        CliWrapper::execute("knife environment show --format=json $env > $env.json");
        $output->writeln("<info>Environment file dumped to $env.json</info>");
    }
}