<?php

declare(strict_types = 1);

namespace asylgrp\workbench\AccountRenderer;

use asylgrp\workbench\TableBuilder;
use asylgrp\workbench\TableBuilderTrait;
use byrokrat\accounting\Dimension\AccountInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\TableCell;

/**
 * Render accounts as simple list
 */
class ListRenderer extends AbstractAccountRenderer
{
    use TableBuilderTrait;

    /**
     * @var integer Number of accounts rendered
     */
    private $count = 0;

    public function initialize(string $header, OutputInterface $output)
    {
        parent::initialize($header, $output);
        $tableBuilder = $this->getTableBuilder();
        $tableBuilder->reset();
        $tableBuilder->addHeader($header);
        $tableBuilder->addColumn('Konto', 4);
        $tableBuilder->addColumn('Beskrivning', 30);
        $tableBuilder->addColumn('Balans', 10, TableBuilder::ALIGN_RIGHT);
    }

    public function renderAccount(AccountInterface $account)
    {
        $this->count++;
        $this->getTableBuilder()->addRow([
            $account->getId(),
            $account->getDescription(),
            $account->getAttribute('summary')->getOutgoingBalance()
        ]);
    }

    public function finalize()
    {
        $tableBuilder = $this->getTableBuilder();
        $tableBuilder->addSeparator();
        $tableBuilder->addRow([new TableCell("{$this->count} konton funna", ['colspan' => '3'])]);
        $tableBuilder->buildTable($this->getOutput())->render();
    }
}
