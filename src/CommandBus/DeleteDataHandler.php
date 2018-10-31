<?php

declare(strict_types = 1);

namespace asylgrp\workbench\CommandBus;

use asylgrp\workbench\DependencyInjection\DispatcherProperty;
use asylgrp\workbench\DependencyInjection\StorageProperty;
use asylgrp\workbench\Event\LogEvent;

final class DeleteDataHandler
{
    use DispatcherProperty, StorageProperty;

    public function handle(DeleteDataCommand $command)
    {
        if ($this->storage->delete($command->getKey())) {
            $this->dispatcher->dispatch(
                LogEvent::INFO,
                new LogEvent("Removed <comment>{$command->getKey()}</comment> from storage")
            );
        }
    }
}
