<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\Storage;

use asylgrp\workbench\Storage\StorageSubscriber;
use asylgrp\workbench\Storage\StorageManager;
use asylgrp\workbench\Event\RemoveItemEvent;
use asylgrp\workbench\Event\StoreItemEvent;
use asylgrp\workbench\Event\LogEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StorageSubscriberSpec extends ObjectBehavior
{
    function let(StorageManager $manager)
    {
        $this->beConstructedWith($manager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StorageSubscriber::CLASS);
    }

    function it_is_an_event_subscriber()
    {
        $this->shouldHaveType(EventSubscriberInterface::CLASS);
    }

    function it_can_subscribe_to_events()
    {
        $this->getSubscribedEvents()->shouldBeLike([
            RemoveItemEvent::NAME => 'onRemoveItem',
            StoreItemEvent::NAME => 'onStoreItem'
        ]);
    }

    function it_removes_items_from_storage(RemoveItemEvent $event, EventDispatcherInterface $dispatcher, $manager)
    {
        $event->getKey()->willReturn('foobar');
        $manager->delete('foobar')->shouldBeCalled()->willReturn(true);
        $dispatcher->dispatch(LogEvent::INFO, Argument::type(LogEvent::CLASS))->shouldBeCalled();
        $this->onRemoveItem($event, RemoveItemEvent::NAME, $dispatcher);
    }

    function it_ignores_removing_unknown_items(RemoveItemEvent $event, EventDispatcherInterface $dispatcher, $manager)
    {
        $event->getKey()->willReturn('foobar');
        $manager->delete('foobar')->shouldBeCalled()->willReturn(false);
        $dispatcher->dispatch(LogEvent::INFO, Argument::type(LogEvent::CLASS))->shouldNotBeCalled();
        $this->onRemoveItem($event, RemoveItemEvent::NAME, $dispatcher);
    }

    function it_stores_items(StoreItemEvent $event, EventDispatcherInterface $dispatcher, $manager)
    {
        $event->getKey()->willReturn('foo');
        $event->getValue()->willReturn('bar');
        $manager->write('foo', 'bar')->shouldBeCalled();
        $dispatcher->dispatch(LogEvent::INFO, Argument::type(LogEvent::CLASS))->shouldBeCalled();
        $this->onStoreItem($event, StoreItemEvent::NAME, $dispatcher);
    }
}
