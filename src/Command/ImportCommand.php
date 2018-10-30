<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Command;

use asylgrp\workbench\Event\ImportEvent;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends AbstractBaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('import');
        $this->setDescription('Import a file');
        $this->setHelp('Allows you to import data from bookkeeping or other sources');
        $this->addArgument('filename', InputArgument::REQUIRED, 'The name of the file to import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!is_readable($input->getArgument('filename')) || !is_file($input->getArgument('filename'))) {
            throw new \RuntimeException("Unable to read file {$input->getArgument('filename')}");
        }

        $this->dispatch(ImportEvent::SIE, new ImportEvent(file_get_contents($input->getArgument('filename'))));
    }
}
