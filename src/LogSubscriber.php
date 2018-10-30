<?php

declare(strict_types = 1);

namespace asylgrp\workbench;

use asylgrp\workbench\Event\LogEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Write log events to output
 */
class LogSubscriber implements EventSubscriberInterface
{
    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public static function getSubscribedEvents()
    {
        return [
            LogEvent::INFO => 'onInfo',
            LogEvent::DEBUG => 'onDebug'
        ];
    }

    /**
     * Write info messages to output
     */
    public function onInfo(LogEvent $event)
    {
        $this->output->writeln($event->getMessage());
    }

    /**
     * Write debug messages to output if verbose
     */
    public function onDebug(LogEvent $event)
    {
        if ($this->output->isVerbose()) {
            $this->output->writeln($event->getMessage());
        }
    }
}
