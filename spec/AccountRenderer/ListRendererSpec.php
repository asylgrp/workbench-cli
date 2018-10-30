<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\AccountRenderer;

use asylgrp\workbench\AccountRenderer\ListRenderer;
use asylgrp\workbench\TableBuilder;
use byrokrat\accounting\Dimension\AssetAccount;
use byrokrat\accounting\Summary;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ListRendererSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ListRenderer::CLASS);
    }

    function it_can_render_a_list(TableBuilder $tableBuilder, OutputInterface $output, Summary $summary, Table $table)
    {
        $account = new AssetAccount('1', 'desc', ['summary' => $summary]);

        $this->setTableBuilder($tableBuilder);

        $tableBuilder->buildTable($output)->willReturn($table)->shouldBeCalled();
        $tableBuilder->reset()->shouldBeCalled();
        $tableBuilder->addHeader('header')->shouldBeCalled();
        $tableBuilder->addColumn(Argument::cetera())->shouldBeCalled();
        $tableBuilder->addSeparator()->shouldBeCalled();
        $tableBuilder->addRow(Argument::any())->shouldBeCalled();

        $this->initialize('header', $output);
        $this->renderAccount($account);
        $this->finalize();

        $table->render()->shouldHaveBeenCalled();
    }
}
