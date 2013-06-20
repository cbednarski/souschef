<?php

namespace cbednarski\souschef\Command;

use cbednarski\souschef\CliWrapper;
use cbednarski\souschef\Environment;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateEnvironment extends Command
{
    protected function configure()
    {
        $this->setName('update');
        $this->setDescription('Update the specified environment using a specified, partial environment file');

        $this->addArgument('environment', InputArgument::REQUIRED, 'environment to update');
        $this->addArgument('mergefile', InputArgument::REQUIRED, 'partial file to merge');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mergefile = $input->getArgument('mergefile');
        if(!is_readable($mergefile)) {
            throw new \Exception('Unable to read merge file from ' . $mergefile);
        }

        $environment = $input->getArgument('environment');

        $scdir = '~/.souschef';
        mkdir($scdir);
        $environment_file = "$scdir/$environment.json";

        $output->writeln("<info>Updating $environment with:</info>");
        $output->writeln(file_get_contents($mergefile));

        CliWrapper::execute("knife environment show --format=json $environment > $environment_file");
        $env = Environment::createFromFile($environment_file);
        $env->mergeFile($mergefile);
        file_put_contents($environment_file, $env->getDataAsJson());
        CliWrapper::execute("knife environment from file $environment_file");
        unlink($environment_file);
    }
}