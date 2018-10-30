<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Command;

use asylgrp\workbench\AccountRenderer\AccountRendererFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BookCommand extends AccountingAwareCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('book');
        $this->setDescription('Display bookkeeping data');
        $this->setHelp('Display bookkeeping data for one or more accounts');
        $this->addArgument('account', InputArgument::OPTIONAL, 'Name or number of account to inspect');
        $this->addOption(
            'format',
            null,
            InputOption::VALUE_REQUIRED,
            'One of ' . AccountRendererFactory::VALID_FORMATS,
            AccountRendererFactory::FORMAT_LEDGER
        );
        $this->addOption('kp', null, InputOption::VALUE_NONE, 'Display only accounts belonging to a contact person');
        $this->addOption('unbalanced', null, InputOption::VALUE_NONE, 'Display only unbalanced accounts');
        $this->addOption('unused', null, InputOption::VALUE_NONE, 'Display only unused accounts');
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

        $renderer = (new AccountRendererFactory)->createAccountRenderer($input->getOption('format'));

        $renderer->initialize("WORKBENCH $input @ " . date('Y-m-d'), $output);

        $accounts->orderById()->each([$renderer, 'renderAccount']);

        $renderer->finalize();
    }
}
