<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * A log event is dispatched each time a pice of information should be logged
 */
class LogEvent extends Event
{
    /**
     * Log level describing detailed debug information
     */
    const DEBUG = 'log.debug';

    /**
     * Log level describing interesting events
     */
    const INFO = 'log.info';

    /**
     * @var string The log message
     */
    private $message;

    /**
     * Set log message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * Get log message
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
