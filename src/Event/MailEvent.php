<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * A mail event is dispatched each time a mail is sent
 */
class MailEvent extends Event
{
    const NAME = 'mail';
}
