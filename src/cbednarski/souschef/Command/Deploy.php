<?php

namespace cbednarski\souschef\Command;

use cbednarski\souschef\CliWrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Deploy extends Command
{
    protected function configure()
    {
        $this->setName('deploy');
        $this->setDescription('Deploy a node or all nodes in an environment');

        $this->addArgument('type', InputArgument::REQUIRED, '"environment", "env", or "node"');
        $this->addArgument('deployable', InputArgument::REQUIRED, 'identifier for the thing being deployed');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');
        if (!in_array($type, array('environment', 'env', 'node'))) {
            throw new \DomainException('I can only deploys nodes and environments');
        }

        $deployable = $input->getArgument('deployable');


        if($type === 'node') {
            $output->writeln("<info>Deploying node $deployable</info>");
            CliWrapper::execute("ssh -t $deployable 'sudo chef-client'");
        } else {
            $output->writeln("<info>Deploying nodes in $deployable</info>");
            CliWrapper::execute("knife ssh \"chef_environment:$deployable\" -a ipaddress 'sudo chef-client'");
        }
    }
}