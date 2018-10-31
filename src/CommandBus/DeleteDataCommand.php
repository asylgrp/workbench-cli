<?php

declare(strict_types = 1);

namespace asylgrp\workbench\CommandBus;

class DeleteDataCommand
{
    /**
     * @var string
     */
    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
