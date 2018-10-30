<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * A sync event is dispatched each time the database is synced with the server
 */
class SyncEvent extends Event
{
    const NAME = 'sync';
}
