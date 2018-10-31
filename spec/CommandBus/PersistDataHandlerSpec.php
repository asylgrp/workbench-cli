<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\CommandBus;

use asylgrp\workbench\CommandBus\PersistDataHandler;
use asylgrp\workbench\CommandBus\PersistDataCommand;
use asylgrp\workbench\Storage\StorageInterface;
use asylgrp\workbench\Event\LogEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PersistDataHandlerSpec extends ObjectBehavior
{
    function let(EventDispatcherInterface $dispatcher, StorageInterface $storage)
    {
        $this->setEventDispatcher($dispatcher);
        $this->setStorage($storage);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PersistDataHandler::CLASS);
    }

    function it_stores_items(PersistDataCommand $command, $dispatcher, $storage)
    {
        $command->getKey()->willReturn('foo');
        $command->getValue()->willReturn('bar');
        $storage->write('foo', 'bar')->shouldBeCalled();
        $dispatcher->dispatch(LogEvent::INFO, Argument::type(LogEvent::CLASS))->shouldBeCalled();
        $this->handle($command);
    }
}
