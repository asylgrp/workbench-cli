<?php

declare(strict_types = 1);

namespace asylgrp\workbench\DependencyInjection;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Use this trait to automatically inject an event dispatcher
 */
trait DispatcherProperty
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @required
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher): void
    {
        $this->dispatcher = $dispatcher;
    }
}
