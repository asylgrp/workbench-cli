<?php

declare(strict_types = 1);

namespace asylgrp\workbench\AccountRenderer;

use asylgrp\workbench\DependencyInjection\TableBuilderProperty;
use asylgrp\workbench\TableBuilder;
use byrokrat\accounting\Dimension\AccountInterface;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Render accounts as a simplified ledger tables
 */
final class MailRenderer implements AccountRendererInterface
{
    use TableBuilderProperty;

    /**
     * @var OutputInterface
     */
    private $output;

    public function initialize(string $header, OutputInterface $output): void
    {
        $this->output = $output;
    }

    public function renderAccount(AccountInterface $account): void
    {
        $this->tableBuilder->reset();
        $this->tableBuilder->addHeader($account->getDescription());
        $this->tableBuilder->addColumn('Datum', 10);
        $this->tableBuilder->addColumn('Beskrivning', 30);
        $this->tableBuilder->addColumn('Summa', 9, TableBuilder::ALIGN_RIGHT);
        $this->tableBuilder->addColumn('Saldo', 10, TableBuilder::ALIGN_RIGHT);

        $currentBalance = $account->getAttribute('summary')->getIncomingBalance();

        $this->tableBuilder->addRow([
            new TableCell("Ingående saldo", ['colspan' => '2']),
            '',
            $currentBalance
        ]);

        $this->tableBuilder->addSeparator();

        foreach ($account->getAttribute('transactions') as $trans) {
            $this->tableBuilder->addRow([
                $trans->getDate()->format('Y-m-d'),
                mb_substr($trans->getDescription(), 0, 30),
                $trans->getAmount(),
                $currentBalance = $currentBalance->add($trans->getAmount())
            ]);
        }

        $this->tableBuilder->addSeparator();

        $this->tableBuilder->addRow([
            new TableCell("Utgående saldo", ['colspan' => '2']),
            '',
            $account->getAttribute('summary')->getOutgoingBalance()
        ]);

        $this->tableBuilder->buildTable($this->output)->render();
    }

    public function finalize(): void
    {
    }
}
