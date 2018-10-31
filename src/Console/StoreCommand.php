<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Console;

use asylgrp\workbench\CommandBus\DeleteDataCommand;
use asylgrp\workbench\DependencyInjection\StorageProperty;
use asylgrp\workbench\DependencyInjection\TableBuilderProperty;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class StoreCommand extends AbstractCommand
{
    use StorageProperty, TableBuilderProperty;

    protected function configure()
    {
        $this
            ->setName('store')
            ->setDescription('Inspect internal storage')
            ->setHelp('Allows you to inspect and manage stored content')
            ->addOption('clear', null, InputOption::VALUE_NONE, 'Clear all items from storage')
            ->addOption('rm', null, InputOption::VALUE_REQUIRED, 'Remove item from storage')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($key = $input->getOption('rm')) {
            $this->commandBus->handle(new DeleteDataCommand($key));
        }

        if ($input->getOption('clear')) {
            foreach ($this->storage->getKeys() as $key) {
                $this->commandBus->handle(new DeleteDataCommand($key));
            }
        }

        $keys = $this->storage->getKeys();
        sort($keys);

        $this->tableBuilder->reset();
        $this->tableBuilder->addHeader('Items in storage <comment>(' . count($keys) . ')</comment>');
        $this->tableBuilder->addColumn('Key');
        $this->tableBuilder->addColumn('Value');

        foreach ($keys as $key) {
            $this->tableBuilder->addRow([
                $key,
                self::stringify($this->storage->read($key))
            ]);
        }

        $this->tableBuilder->buildTable($output)->render();
    }

    private static function stringify($value): string
    {
        if (is_object($value)) {
            return 'Object';
        }

        return (string)$value;
    }
}
