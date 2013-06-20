<?php

namespace cbednarski\souschef\Command;

use cbednarski\souschef\CliWrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Bootstrap extends Command
{
    protected function configure()
    {
        $this->setName('bootstrap');
        $this->setDescription('Destroy and bootstrap the specified node');

        $this->addArgument('environment', InputArgument::REQUIRED);
        $this->addArgument('node', InputArgument::REQUIRED, 'hostname (will also be used as the node name)');
        $this->addOption('run_list', null, InputOption::VALUE_OPTIONAL, "run_list to add to the node");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $environment = $input->getArgument('environment');
        $node = $input->getArgument('node');
        $run_list = $input->getArgument('run_list');

        // Cleanup
        CliWrapper::execute("knife node delete -y $node");
        CliWrapper::execute("knife client delete -y $node");
        CliWrapper::execute("ssh -l root $node 'yum remove -y chef'");
        CliWrapper::execute("ssh -l root $node 'rm -rf /etc/chef'");
        CliWrapper::execute("ssh -l root $node 'rm -rf /opt/chef'");
        CliWrapper::execute("ssh -l root $node 'rm -rf /var/chef'");
        CliWrapper::execute("ssh -l root $node 'rm -rf /var/cache/chef'");

        // Bootstrap
        CliWrapper::execute("knife bootstrap -E $environment -x root -N $node --sudo $node");
        CliWrapper::execute("scp ~/.chef/encrypted_data_bag_secret root@$node:/etc/chef");

        if($run_list) {
            CliWrapper::execute("knife node run_list add $node '$run_list'");
        }

        // Deploy
        CliWrapper::execute("ssh -l root $node 'chef-client'");
    }
}