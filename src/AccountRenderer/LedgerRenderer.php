<?php

declare(strict_types = 1);

namespace asylgrp\workbench\AccountRenderer;

use asylgrp\workbench\DependencyInjection\TableBuilderProperty;
use asylgrp\workbench\TableBuilder;
use byrokrat\accounting\Dimension\AccountInterface;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Render accounts as ledger tables
 */
final class LedgerRenderer implements AccountRendererInterface
{
    use TableBuilderProperty;

    /**
     * @var OutputInterface
     */
    private $output;

    public function initialize(string $header, OutputInterface $output): void
    {
        $this->output = $output;
        $this->tableBuilder->reset();
        $this->tableBuilder->addColumn($header, 87);
        $this->tableBuilder->buildTable($output)->render();
    }

    public function renderAccount(AccountInterface $account): void
    {
        if (!$account->hasAttribute('summary')) {
            throw new \LogicException('Unable to render unprocessed account');
        }

        $accountSummary = $account->getAttribute('summary');

        $this->tableBuilder->reset();
        $this->tableBuilder->addHeader(strtoupper("{$account->getId()} {$account->getDescription()}"));
        $this->tableBuilder->addColumn('Ver', 4);
        $this->tableBuilder->addColumn('Datum', 10);
        $this->tableBuilder->addColumn('Beskrivning', 30);
        $this->tableBuilder->addColumn('Debet', 9, TableBuilder::ALIGN_RIGHT);
        $this->tableBuilder->addColumn('Kredit', 9, TableBuilder::ALIGN_RIGHT);
        $this->tableBuilder->addColumn('Saldo', 10, TableBuilder::ALIGN_RIGHT);

        $currentBalance = $accountSummary->getIncomingBalance();

        $this->tableBuilder->addRow([
            new TableCell("Ingående balans", ['colspan' => '3']),
            '',
            '',
            $currentBalance
        ]);

        $this->tableBuilder->addSeparator();

        foreach ($account->getAttribute('transactions') ?: [] as $trans) {
            $this->tableBuilder->addRow([
                $trans->getAttribute('ver_num'),
                $trans->getTransactionDate()->format('Y-m-d'),
                mb_substr($trans->getDescription(), 0, 30),
                $trans->getAmount()->isPositive() ? $trans->getAmount() : '',
                $trans->getAmount()->isNegative() ? $trans->getAmount()->getAbsolute() : '',
                $currentBalance = $currentBalance->add($trans->getAmount())
            ]);
        }

        $this->tableBuilder->addSeparator();

        $this->tableBuilder->addRow([
            new TableCell("Omslutning", ['colspan' => '3']),
            $accountSummary->getDebit()->getAbsolute(),
            $accountSummary->getCredit()->getAbsolute(),
            ''
        ]);

        $this->tableBuilder->addSeparator();

        $this->tableBuilder->addRow([
            new TableCell("Utgående balans", ['colspan' => '3']),
            '',
            '',
            $accountSummary->getOutgoingBalance()
        ]);

        $this->tableBuilder->buildTable($this->output)->render();
    }

    public function finalize(): void
    {
    }
}
