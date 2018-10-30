<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Import;

use asylgrp\workbench\Event\ImportEvent;
use asylgrp\workbench\Event\StoreItemEvent;
use asylgrp\workbench\Storage\StorageManager;
use byrokrat\accounting\Processor\TransactionProcessor;
use byrokrat\accounting\Sie4\Parser\Sie4Parser;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Import sie files to storage
 */
class ImportSieAction
{
    /**
     * @var Sie4Parser
     */
    private $sieParser;

    /**
     * @var StorageManager
     */
    private $storageManager;

    public function __construct(Sie4Parser $sieParser, StorageManager $storageManager)
    {
        $this->sieParser = $sieParser;
        $this->storageManager = $storageManager;
    }

    public function __invoke(ImportEvent $event, string $name, EventDispatcherInterface $dispatcher)
    {
        $book = $this->sieParser->parse($event->getContents());

        // TODO should we inject this maybe!!!!!!!
        (new TransactionProcessor)->processContainer($book);

        if ('4' != $book->getAttribute('sie_version')) {
            throw new \RuntimeException("Expecting sie version 4, found: {$book->getAttribute('sie_version')}");
        }

        $expectedOrg = $this->storageManager->read('org_name');
        $sieOrg = $book->hasAttribute('company_name') ? $book->getAttribute('company_name') : '';

        if ($expectedOrg && 0 !== strcasecmp($sieOrg, $expectedOrg)) {
            throw new \RuntimeException("SIE concerns '$sieOrg', expecting '$expectedOrg'");
        }

        if (!$book->hasAttribute('taxation_year')) {
            throw new \RuntimeException('Taxation year must be specified in SIE file');
        }

        $year = $book->getAttribute('taxation_year');

        $dispatcher->dispatch(StoreItemEvent::NAME, new StoreItemEvent("book_$year", $book));

        if ($year > (int)$this->storageManager->read('current_accounting_year')) {
            $dispatcher->dispatch(StoreItemEvent::NAME, new StoreItemEvent('current_accounting_year', (string)$year));
        }
    }
}
