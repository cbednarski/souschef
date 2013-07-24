<?php

namespace cbednarski\souschef\Command;

use cbednarski\souschef\CliWrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListEnvironment extends Command
{
    protected function configure()
    {
        $this->setName('environment-list');
        $this->setAliases(array('el'));
        $this->setDescription('Show nodes in an environment');

        $this->addArgument('environment', InputArgument::REQUIRED, 'environment to list');
        $this->addOption('attribute', 'a', InputOption::VALUE_REQUIRED, 'which arguments to pring');

        $this->addArgument('parameters', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'options for knife search node');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $env = $input->getArgument("environment");
        $params = join(' ', $input->getArgument("parameters"));
        $attr = $input->getOption('attribute');
        if (!empty($attr)) {
            $params .= " -a $attr";
        }
        $output->writeln("<info>Listing nodes in $env</info>");
        CliWrapper::execute("knife search node 'chef_environment:$env' $params");
    }
}
