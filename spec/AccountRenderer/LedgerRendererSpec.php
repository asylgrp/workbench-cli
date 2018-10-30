<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\AccountRenderer;

use asylgrp\workbench\AccountRenderer\LedgerRenderer;
use asylgrp\workbench\TableBuilder;
use byrokrat\accounting\Dimension\AssetAccount;
use byrokrat\accounting\Summary;
use byrokrat\amount\Amount;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LedgerRendererSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(LedgerRenderer::CLASS);
    }

    function it_can_render(TableBuilder $tableBuilder, OutputInterface $output, Summary $summary, Table $table)
    {
        $summary->getDebit()->willReturn(new Amount('1'));
        $summary->getCredit()->willReturn(new Amount('1'));
        $summary->getIncomingBalance()->willReturn(new Amount('0'));
        $summary->getOutgoingBalance()->willReturn(new Amount('0'));

        $account = new AssetAccount(
            '1',
            'account-description',
            [
                'summary' => $summary->getWrappedObject(),
                'transactions' => []
            ]
        );

        $this->setTableBuilder($tableBuilder);

        $tableBuilder->buildTable($output)->willReturn($table)->shouldBeCalled();
        $tableBuilder->reset()->shouldBeCalled();
        $tableBuilder->addHeader('1 ACCOUNT-DESCRIPTION')->shouldBeCalled();
        $tableBuilder->addColumn(Argument::cetera())->shouldBeCalled();
        $tableBuilder->addSeparator()->shouldBeCalled();
        $tableBuilder->addRow(Argument::any())->shouldBeCalled();

        $this->initialize('header', $output);
        $this->renderAccount($account);
        $this->finalize();

        $table->render()->shouldHaveBeenCalled();
    }
}
