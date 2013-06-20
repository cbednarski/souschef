<?php

namespace cbednarski\souschef\Command;

use cbednarski\souschef\CliWrapper;
use cbednarski\souschef\Environment;
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
        $this->setDescription(
            'Promoting one environment in a blue-green pair will copy all of the configuration to the matching environment pair'
        );

        $this->addArgument('environment', InputArgument::REQUIRED, 'The environment to promote');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $environment = $this->getEnvironmentRoot($input->getArgument('environment'));
        $color = $this->getColor($input->getArgument('environment'));
        $other_color = $this->getOtherColor($color);

        $output->writeln("Updating $environment-$other_color to match $environment-$color");

        $scdir = '~/.souschef';
        mkdir($scdir);

        // TODO Change this to read from stdout instead of a file to eliminate weirdness with multiple processes
        // TODO changing the same file at the same time
        $source_file = "$scdir/$environment-$color.json";
        $target_file = "$scdir/$environment-$other_color.json";
        CliWrapper::execute("knife environment show --format=json $environment-$color > $source_file");
        CliWrapper::execute("knife environment show --format=json $environment-$color > $target_file");
        $source_env = Environment::createFromFile($source_file);
        $target_env = Environment::createFromFile($target_file);

        $temp = $source_env->getData();
        $temp->name = $target_env->getName();
        $temp->description = $target_env->getDescription();
        $target_env->setData($temp);
        file_put_contents($target_file, $target_env->getDataAsJson());
        CliWrapper::execute("knife environment from file $target_file");

        unlink($source_file);
        unlink($target_file);
    }

    protected function getEnvironmentRoot($environment)
    {
        if (preg_match('/^(.*)-(?:green|blue)$/', $environment, $matches)) {
            return $matches[1];
        }

        return false;

    }

    protected function getColor($environment)
    {
        if (preg_match('/^.*-(green|blue)$/', $environment, $matches)) {
            return $matches[1];
        }

        return false;
    }

    protected function getOtherColor($color)
    {
        if ($color === 'green') {
            return 'blue';
        } elseif ($color === 'blue') {
            return 'green';
        } else {
            throw new \DomainException('Color must be blue or green');
        }
    }
}