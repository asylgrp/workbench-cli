<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Console;

use asylgrp\workbench\Event\BanEvent;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BanCommand extends AbstractBaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('ban');
        $this->setDescription('Ban a contact from receiving payouts');
        $this->setHelp('Allows you to temporarily ban a contact person from receiving payouts');
        $this->addArgument('contact', InputArgument::REQUIRED, 'The id number of the contact person');
        $this->addArgument('desc', InputArgument::REQUIRED, 'A description of why');
        $this->addOption('unban', 'u', InputOption::VALUE_NONE, 'Unban a previously banned contact');
        $this->addOption('no-sync', null, InputOption::VALUE_NONE, 'Running command should not imply a server sync');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dispatch(
            $input->getOption('unban') ? BanEvent::UNBAN : BanEvent::BAN,
            new BanEvent(
                $input->getArgument('contact'),
                $input->getArgument('desc'),
                $input->getOption('no-sync')
            )
        );
    }
}
