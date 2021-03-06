<?php

namespace cbednarski\souschef\Command;

use cbednarski\souschef\CliWrapper;
use cbednarski\souschef\Environment;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
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
        $this->addArgument('patchfile', InputArgument::REQUIRED, 'patch to apply to the environment');
        $this->addOption('diff', 'd', InputOption::VALUE_NONE, 'show the changes to be applied before updating the environment');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $patchfile = $input->getArgument('patchfile');
        if(!is_readable($patchfile)) {
            throw new \Exception('Unable to read merge file from ' . $patchfile);
        }
        $environment = $input->getArgument('environment');

        $scdir = $_SERVER['HOME'] . '/.souschef';
        if (!is_dir($scdir)) {
            mkdir($scdir);
        }
        $environment_file = "$scdir/$environment.json";

        $output->writeln("<info>Updating $environment with:</info>");
        $output->writeln(file_get_contents($patchfile));

        CliWrapper::execute("knife environment show --format=json $environment > $environment_file");
        $env = Environment::createFromFile($environment_file);
        if ($input->getOption('diff')) {
            $output->writeln("<info>Requested changes:</info>");
            $changes = $env->getDiffFromFile($patchfile);
            if ($changes) {
                $output->writeln($changes);
                /* @var DialogHelper $dialog */
                $dialog = $this->getHelperSet()->get('dialog');
                if (!$dialog->askConfirmation($output, 'Perform these changes? (Y/n) ')) {
                    return;
                }
            } else {
                $output->writeln("<info>No changes.</info>");
                return;
            }
        }
        $env->applyPatchfile($patchfile);
        file_put_contents($environment_file, $env->getDataAsJson());
        CliWrapper::execute("knife environment from file $environment_file");
        unlink($environment_file);
    }
}
