<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Console;

use asylgrp\workbench\AccountRenderer\AccountRendererContainer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class BookCommand extends AccountingAwareCommand
{
    /**
     * @var AccountRendererContainer
     */
    private $accountRendererContainer;

    public function __construct(AccountRendererContainer $accountRendererContainer)
    {
        $this->accountRendererContainer = $accountRendererContainer;
        parent::__construct();
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->setName('book')
            ->setDescription('Display bookkeeping data')
            ->setHelp('Display bookkeeping data for one or more accounts')
            ->addArgument('account', InputArgument::OPTIONAL, 'Name or number of account to inspect')
            ->addOption(
                'format',
                null,
                InputOption::VALUE_REQUIRED,
                'One of ' . $this->accountRendererContainer->getAccountRendererNames(),
                'ledger'
            )
            ->addOption('kp', null, InputOption::VALUE_NONE, 'Display only accounts belonging to a contact person')
            ->addOption('unbalanced', null, InputOption::VALUE_NONE, 'Display only unbalanced accounts')
            ->addOption('unused', null, InputOption::VALUE_NONE, 'Display only unused accounts')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $accounts = $this->getAccounting()->select()->accounts()->whereUnique();

        if ($input->getOption('kp')) {
            $accounts = $accounts->whereContactPerson();
        }

        if ($input->getOption('unbalanced')) {
            $accounts = $accounts->whereUnbalanced();
        }

        if ($input->getOption('unused')) {
            $accounts = $accounts->whereUnused();
        }

        if ($accountId = $input->getArgument('account')) {
            $accounts = is_numeric($accountId)
                ? $accounts->whereAccount($accountId)
                : $accounts->whereDescription("/$accountId/i");
        }

        $renderer = $this->accountRendererContainer->getAccountRenderer($input->getOption('format'));

        $renderer->initialize('WORKBENCH @ ' . date('Y-m-d'), $output);

        $accounts->orderById()->each([$renderer, 'renderAccount']);

        $renderer->finalize();
    }
}
