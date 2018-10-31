<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Console;

use asylgrp\workbench\CommandBus\PersistDataCommand;
use asylgrp\workbench\DependencyInjection\StorageProperty;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

final class InitCommand extends AbstractCommand
{
    use StorageProperty;

    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Initialize the database')
            ->setHelp('Initialize toolox installation')
            ->addOption('org-name', null, InputOption::VALUE_REQUIRED, 'Name of organization')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $newOrgName = $input->getOption('org-name');
        $currentOrgName = $this->storage->read('org_name');

        if (!$newOrgName) {
            $questionHelper = $this->getHelper('question');
            $question = new Question(
                "Enter name of organization [<info>$currentOrgName</info>]: ",
                $currentOrgName
            );
            $newOrgName = $questionHelper->ask($input, $output, $question);
        }

        if ($newOrgName != $currentOrgName) {
            $this->commandBus->handle(new PersistDataCommand('org_name', $newOrgName));
        }
    }
}
