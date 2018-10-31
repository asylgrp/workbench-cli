<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Console;

use asylgrp\workbench\CommandBus\ImportSie4Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ImportCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('import')
            ->setDescription('Import a file')
            ->setHelp('Allows you to import data from bookkeeping or other sources')
            ->addArgument('filename', InputArgument::REQUIRED, 'The name of the file to import')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fname = $input->getArgument('filename');

        if (!is_readable($fname) || !is_file($fname)) {
            throw new \RuntimeException("Unable to read file $fname");
        }

        $this->commandBus->handle(new ImportSie4Command($fname, file_get_contents($fname)));
    }
}
