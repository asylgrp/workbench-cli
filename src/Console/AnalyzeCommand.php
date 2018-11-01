<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use asylgrp\matchmaker\MatchMaker;
use asylgrp\matchmaker\Filter\FilterInterface;
use asylgrp\matchmaker\AccountingMatchableFactory;

final class AnalyzeCommand extends AccountingAwareCommand
{
    /**
     * @var MatchMaker
     */
    private $matchMaker;

    /**
     * @var FilterInterface
     */
    private $matchFilter;

    public function __construct(MatchMaker $matchMaker, FilterInterface $matchFilter)
    {
        $this->matchMaker = $matchMaker;
        $this->matchFilter = $matchFilter;
        parent::__construct();
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->setName('analyze')
            ->setDescription('Analyze bookkeeping data')
            ->setHelp('Analyze bookkeeping data to find possible bans or duplicates')
            ->addArgument(
                'account',
                InputArgument::OPTIONAL,
                'Name or number of account to inspect'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $accounts = $this->getAccounting()->select()->accounts()->whereUnique()->whereContactPerson();

        if ($accountId = $input->getArgument('account')) {
            $accounts = is_numeric($accountId)
                ? $accounts->whereAccount($accountId)
                : $accounts->whereDescription("/$accountId/i");
        }

        $matchableFactory = AccountingMatchableFactory::createFactoryForYear(
            (int)$this->getAccountingYear()
        );

        foreach ($accounts as $account) {
            $matchables = $matchableFactory->createMatchablesForAccount($account, $this->getAccounting());
            $matches = $this->matchMaker->match(...$matchables);
            $result = $this->matchFilter->evaluate($matches);
            if ($result->isSuccess()) {
                $output->writeln(sprintf(
                    '%s %s: %s',
                    $account->getId(),
                    $account->getDescription(),
                    $result->getMessage()
                ));
            }
        }
    }
}
