<?php

declare(strict_types = 1);

namespace asylgrp\workbench\CommandBus;

class ImportSie4Command
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $contents;

    public function __construct(string $filename, string $contents)
    {
        $this->filename = $filename;
        $this->contents = $contents;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getContents(): string
    {
        return $this->contents;
    }
}
