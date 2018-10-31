<?php

declare(strict_types = 1);

namespace asylgrp\workbench\CommandBus;

use asylgrp\workbench\DependencyInjection\DispatcherProperty;
use asylgrp\workbench\DependencyInjection\StorageProperty;
use asylgrp\workbench\Event\LogEvent;

final class PersistDataHandler
{
    use DispatcherProperty, StorageProperty;

    public function handle(PersistDataCommand $command)
    {
        $this->storage->write($command->getKey(), $command->getValue());
        $this->dispatcher->dispatch(
            LogEvent::INFO,
            new LogEvent("Added <comment>{$command->getKey()}</comment> to storage")
        );
    }
}
