<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Sync;

use asylgrp\workbench\Event\SyncEvent;

/**
 * Sync local storage with server
 */
class SyncAction
{
    public function __invoke(SyncEvent $event)
    {
        throw new \Exception(<<<'EOD'
TODO implementera SyncAction

* Synkronisera lokal databas med server
EOD
        );
    }
}
