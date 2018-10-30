<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Command;

use asylgrp\workbench\Event\StoreItemEvent;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class InitCommand extends AbstractBaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('init');
        $this->setDescription('Initialize the database');
        $this->setHelp('Initialize toolox installation');
        $this->addOption('org-name', null, InputOption::VALUE_REQUIRED, 'Name of organization');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $newOrgName = $input->getOption('org-name');
        $currentOrgName = $this->getContainer()->get('storage_manager')->read('org_name');

        if (!$newOrgName) {
            $questionHelper = $this->getHelper('question');
            $question = new Question(
                "Enter name of organization [<info>$currentOrgName</info>]: ",
                $currentOrgName
            );
            $newOrgName = $questionHelper->ask($input, $output, $question);
        }

        if ($newOrgName != $currentOrgName) {
            $this->dispatch(StoreItemEvent::NAME, new StoreItemEvent('org_name', $newOrgName));
        }
    }
}
