<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Dispatched each time a file should be imported into storage
 */
class ImportEvent extends Event
{
    /**
     * Import an SIE file
     */
    const SIE = 'import.sie';

    /**
     * @var string
     */
    private $contents;

    public function __construct(string $contents)
    {
        $this->contents = $contents;
    }

    public function getContents(): string
    {
        return $this->contents;
    }
}
