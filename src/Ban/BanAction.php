<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Ban;

use asylgrp\workbench\Event\BanEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Ban a contact person
 */
class BanAction
{
    public function __invoke(BanEvent $event, string $name, EventDispatcherInterface $dispatcher)
    {
        throw new \Exception(<<<'EOD'
TODO implementera BanAction

* Ban eller Unban beroende på $name
* Skicka payload direkt till server
* Implicit SyncEvent (om $event->impliesServerSync())
* Genererar mail (MailEvent)
EOD
        );
    }
}
