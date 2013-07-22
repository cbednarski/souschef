<?php

namespace cbednarski\souschef\Command;

use cbednarski\souschef\CliWrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Ssh extends Command
{
    protected function configure()
    {
        $this->setName('ssh');
        $this->setDescription('Connect to nodes using ssh');

        $this->addOption('name', 'N', InputOption::VALUE_REQUIRED, "Ssh into a node by name.");
        $this->addOption('ipaddress', 'i', InputOption::VALUE_REQUIRED, "Ssh into a node by IP address.");
        $this->addOption('environment', 'e', InputOption::VALUE_REQUIRED, "Ssh into all nodes in an environment.");
        $this->addOption('command', 'c', InputOption::VALUE_REQUIRED, "Which command to run.", 'cssh');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $input->getOption('command');
        $environment = $input->getOption('environment');
        $name = $input->getOption('name');
        $ipaddress = $input->getOption('ipaddress');

        if (!empty($environment)) {
            CliWrapper::execute("knife ssh 'chef_environment:$environment' $command");
        } elseif (!empty($name)) {
            CliWrapper::execute("knife ssh 'name:$name' $command");
        } elseif (!empty($ipaddress)) {
            CliWrapper::execute("knife ssh 'ipaddress:$name' $command");
        } else {
            throw new \InvalidArgumentException("You need to specify an environment, name, or ip.");
        }
    }
}
