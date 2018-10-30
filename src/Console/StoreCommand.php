<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Console;

use asylgrp\workbench\Event\RemoveItemEvent;
use asylgrp\workbench\TableBuilder;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StoreCommand extends AbstractBaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('store');
        $this->setDescription('Inspect internal storage');
        $this->setHelp('Allows you to inspect and manage stored content');
        $this->addOption('clear', null, InputOption::VALUE_NONE, 'Clear all items from storage');
        $this->addOption('rm', null, InputOption::VALUE_REQUIRED, 'Remove item from storage');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $storage = $this->getContainer()->get('storage_manager');

        if ($key = $input->getOption('rm')) {
            $this->dispatch(RemoveItemEvent::NAME, new RemoveItemEvent($key));
        }

        if ($input->getOption('clear')) {
            foreach ($storage->getKeys() as $key) {
                $this->dispatch(RemoveItemEvent::NAME, new RemoveItemEvent($key));
            }
        }

        $keys = $storage->getKeys();
        sort($keys);

        $tableBuilder = new tableBuilder;
        $tableBuilder->addHeader('Items in storage <comment>(' . count($keys) . ')</comment>');
        $tableBuilder->addColumn('Key');
        $tableBuilder->addColumn('Value');

        foreach ($keys as $key) {
            $tableBuilder->addRow([
                $key,
                self::stringify($storage->read($key))
            ]);
        }

        $tableBuilder->buildTable($output)->render();
    }

    private static function stringify($value): string
    {
        if (is_object($value)) {
            return 'Object';
        }

        return (string)$value;
    }
}
