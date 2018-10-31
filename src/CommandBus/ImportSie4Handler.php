<?php

declare(strict_types = 1);

namespace asylgrp\workbench\CommandBus;

use asylgrp\workbench\DependencyInjection\CommandBusProperty;
use asylgrp\workbench\DependencyInjection\StorageProperty;
use byrokrat\accounting\Processor\ProcessorInterface;
use byrokrat\accounting\Sie4\Parser\Sie4Parser;

final class ImportSie4Handler
{
    use CommandBusProperty, StorageProperty;

    /**
     * @var Sie4Parser
     */
    private $sieParser;

    /**
     * @var ProcessorInterface
     */
    private $bookProcessor;

    public function __construct(Sie4Parser $sieParser, ProcessorInterface $bookProcessor)
    {
        $this->sieParser = $sieParser;
        $this->bookProcessor = $bookProcessor;
    }

    public function handle(ImportSie4Command $command)
    {
        $book = $this->sieParser->parse($command->getContents());

        $this->bookProcessor->processContainer($book);

        if ('4' != $book->getAttribute('sie_version')) {
            throw new \RuntimeException("Expecting sie version 4, found: {$book->getAttribute('sie_version')}");
        }

        $expectedOrg = $this->storage->read('org_name');
        $sieOrg = $book->hasAttribute('company_name') ? $book->getAttribute('company_name') : '';

        if ($expectedOrg && 0 !== strcasecmp($sieOrg, $expectedOrg)) {
            throw new \RuntimeException("SIE concerns '$sieOrg', expecting '$expectedOrg'");
        }

        if (!$book->hasAttribute('taxation_year')) {
            throw new \RuntimeException('Taxation year must be specified in SIE file');
        }

        $year = $book->getAttribute('taxation_year');

        $this->commandBus->handle(new PersistDataCommand("book_$year", $book));

        if ($year > (int)$this->storage->read('current_accounting_year')) {
            $this->commandBus->handle(new PersistDataCommand('current_accounting_year', (string)$year));
        }
    }
}
