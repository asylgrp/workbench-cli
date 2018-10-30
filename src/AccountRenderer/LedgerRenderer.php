<?php

declare(strict_types = 1);

namespace asylgrp\workbench\AccountRenderer;

use asylgrp\workbench\TableBuilder;
use asylgrp\workbench\TableBuilderTrait;
use byrokrat\accounting\Dimension\AccountInterface;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Render accounts as ledger tables
 */
final class LedgerRenderer extends AbstractAccountRenderer
{
    use TableBuilderTrait;

    public function initialize(string $header, OutputInterface $output)
    {
        parent::initialize($header, $output);
        $tableBuilder = $this->getTableBuilder();
        $tableBuilder->reset();
        $tableBuilder->addColumn($header, 87);
        $tableBuilder->buildTable($output)->render();
    }

    public function renderAccount(AccountInterface $account)
    {
        if (!$account->hasAttribute('summary')) {
            throw new \LogicException('Unable to render unprocessed account');
        }

        $accountSummary = $account->getAttribute('summary');

        $tableBuilder = $this->getTableBuilder();

        $tableBuilder->reset();
        $tableBuilder->addHeader(strtoupper("{$account->getId()} {$account->getDescription()}"));
        $tableBuilder->addColumn('Ver', 4);
        $tableBuilder->addColumn('Datum', 10);
        $tableBuilder->addColumn('Beskrivning', 30);
        $tableBuilder->addColumn('Debet', 9, TableBuilder::ALIGN_RIGHT);
        $tableBuilder->addColumn('Kredit', 9, TableBuilder::ALIGN_RIGHT);
        $tableBuilder->addColumn('Saldo', 10, TableBuilder::ALIGN_RIGHT);

        $currentBalance = $accountSummary->getIncomingBalance();

        $tableBuilder->addRow([
            new TableCell("Ingående balans", ['colspan' => '3']),
            '',
            '',
            $currentBalance
        ]);

        $tableBuilder->addSeparator();

        foreach ($account->getAttribute('transactions') ?: [] as $trans) {
            $tableBuilder->addRow([
                $trans->getAttribute('ver_num'),
                $trans->getTransactionDate()->format('Y-m-d'),
                mb_substr($trans->getDescription(), 0, 30),
                $trans->getAmount()->isPositive() ? $trans->getAmount() : '',
                $trans->getAmount()->isNegative() ? $trans->getAmount()->getAbsolute() : '',
                $currentBalance = $currentBalance->add($trans->getAmount())
            ]);
        }

        $tableBuilder->addSeparator();

        $tableBuilder->addRow([
            new TableCell("Omslutning", ['colspan' => '3']),
            $accountSummary->getDebit()->getAbsolute(),
            $accountSummary->getCredit()->getAbsolute(),
            ''
        ]);

        $tableBuilder->addSeparator();

        $tableBuilder->addRow([
            new TableCell("Utgående balans", ['colspan' => '3']),
            '',
            '',
            $accountSummary->getOutgoingBalance()
        ]);

        $tableBuilder->buildTable($this->getOutput())->render();
    }
}
