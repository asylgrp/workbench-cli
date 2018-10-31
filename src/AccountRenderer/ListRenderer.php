<?php

declare(strict_types = 1);

namespace asylgrp\workbench\AccountRenderer;

use asylgrp\workbench\DependencyInjection\TableBuilderProperty;
use asylgrp\workbench\TableBuilder;
use byrokrat\accounting\Dimension\AccountInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\TableCell;

/**
 * Render accounts as simple list
 */
final class ListRenderer implements AccountRendererInterface
{
    use TableBuilderProperty;

    /**
     * @var integer Number of accounts rendered
     */
    private $count = 0;

    /**
     * @var OutputInterface
     */
    private $output;

    public function initialize(string $header, OutputInterface $output): void
    {
        $this->output = $output;
        $this->tableBuilder->reset();
        $this->tableBuilder->addHeader($header);
        $this->tableBuilder->addColumn('Konto', 4);
        $this->tableBuilder->addColumn('Beskrivning', 30);
        $this->tableBuilder->addColumn('Balans', 10, TableBuilder::ALIGN_RIGHT);
    }

    public function renderAccount(AccountInterface $account): void
    {
        $this->count++;
        $this->tableBuilder->addRow([
            $account->getId(),
            $account->getDescription(),
            $account->getAttribute('summary')->getOutgoingBalance()
        ]);
    }

    public function finalize(): void
    {
        $this->tableBuilder->addSeparator();
        $this->tableBuilder->addRow([new TableCell("{$this->count} konton funna", ['colspan' => '3'])]);
        $this->tableBuilder->buildTable($this->output)->render();
    }
}
