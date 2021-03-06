<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Console;

use asylgrp\workbench\DependencyInjection\StorageProperty;
use asylgrp\workbench\Event\LogEvent;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use byrokrat\accounting\Container;

abstract class AccountingAwareCommand extends AbstractCommand
{
    use StorageProperty;

    /**
     * @var Container Bookkeeping for the specified year
     */
    private $book;

    /**
     * @var string Specified accounting year
     */
    private $year;

    protected function configure()
    {
        parent::configure();
        $this->addOption('year', 'y', InputOption::VALUE_REQUIRED, 'Accounting year');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->year = $input->getOption('year')
            ?: $this->storage->read('current_accounting_year')
            ?: date('Y');

        if (strlen($this->year) == 2) {
            $this->year = substr(date('Y'), 0, 2) . $this->year;
        }

        $this->book = $this->storage->read("book_{$this->year}");

        if (is_null($this->book)) {
            throw new \RuntimeException("Unknown accounting year {$this->year}");
        }

        $this->dispatcher->dispatch(
            LogEvent::DEBUG,
            new LogEvent("Using accounting year <info>{$this->year}</info>")
        );
    }

    protected function getAccounting(): Container
    {
        return $this->book;
    }

    protected function getAccountingYear(): string
    {
        return $this->year;
    }
}
