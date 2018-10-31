<?php

declare(strict_types = 1);

namespace asylgrp\workbench\DependencyInjection;

use League\Tactician\CommandBus;

/**
 * Use this trait to automatically inject the command bus
 */
trait CommandBusProperty
{
    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * @required
     */
    public function setCommandBus(CommandBus $commandBus): void
    {
        $this->commandBus = $commandBus;
    }
}
