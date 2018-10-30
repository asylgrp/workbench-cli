<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Storage;

use asylgrp\workbench\Event\RemoveItemEvent;
use asylgrp\workbench\Event\StoreItemEvent;
use asylgrp\workbench\Event\LogEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Store and remove items from internal storage
 */
class StorageSubscriber implements EventSubscriberInterface
{
    /**
     * @var StorageManager
     */
    private $manager;

    public function __construct(StorageManager $manager)
    {
        $this->manager = $manager;
    }

    public static function getSubscribedEvents()
    {
        return [
            RemoveItemEvent::NAME => 'onRemoveItem',
            StoreItemEvent::NAME => 'onStoreItem'
        ];
    }

    public function onRemoveItem(RemoveItemEvent $event, string $name, EventDispatcherInterface $dispatcher)
    {
        if ($this->manager->delete($event->getKey())) {
            $dispatcher->dispatch(
                LogEvent::INFO,
                new LogEvent("Removed <comment>{$event->getKey()}</comment> from storage")
            );
        }
    }

    public function onStoreItem(StoreItemEvent $event, string $name, EventDispatcherInterface $dispatcher)
    {
        $this->manager->write($event->getKey(), $event->getValue());
        $dispatcher->dispatch(
            LogEvent::INFO,
            new LogEvent("Added <comment>{$event->getKey()}</comment> to storage")
        );
    }
}
