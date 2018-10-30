<?php

declare(strict_types = 1);

namespace asylgrp\workbench\AccountRenderer;

use asylgrp\workbench\TableBuilder;
use asylgrp\workbench\TableBuilderTrait;
use byrokrat\accounting\Dimension\AccountInterface;
use Symfony\Component\Console\Helper\TableCell;

/**
 * Render accounts as a simplified ledger tables
 */
class MailRenderer extends AbstractAccountRenderer
{
    use TableBuilderTrait;

    public function renderAccount(AccountInterface $account)
    {
        $tableBuilder = $this->getTableBuilder();

        $tableBuilder->reset();
        $tableBuilder->addHeader($account->getDescription());
        $tableBuilder->addColumn('Datum', 10);
        $tableBuilder->addColumn('Beskrivning', 30);
        $tableBuilder->addColumn('Summa', 9, TableBuilder::ALIGN_RIGHT);
        $tableBuilder->addColumn('Saldo', 10, TableBuilder::ALIGN_RIGHT);

        $currentBalance = $account->getAttribute('summary')->getIncomingBalance();

        $tableBuilder->addRow([
            new TableCell("Ingående saldo", ['colspan' => '2']),
            '',
            $currentBalance
        ]);

        $tableBuilder->addSeparator();

        foreach ($account->getAttribute('transactions') as $trans) {
            $tableBuilder->addRow([
                $trans->getDate()->format('Y-m-d'),
                mb_substr($trans->getDescription(), 0, 30),
                $trans->getAmount(),
                $currentBalance = $currentBalance->add($trans->getAmount())
            ]);
        }

        $tableBuilder->addSeparator();

        $tableBuilder->addRow([
            new TableCell("Utgående saldo", ['colspan' => '2']),
            '',
            $account->getAttribute('summary')->getOutgoingBalance()
        ]);

        $tableBuilder->buildTable($this->getOutput())->render();
    }
}
