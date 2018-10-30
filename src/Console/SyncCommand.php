<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Console;

use asylgrp\workbench\Event\SyncEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncCommand extends AbstractBaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('sync');
        $this->setDescription('Sync database with server');
        $this->setHelp('Syn the local database with registered workbench server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dispatch(SyncEvent::NAME, new SyncEvent);
    }
}
