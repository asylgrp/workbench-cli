<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\CommandBus;

use asylgrp\workbench\CommandBus\DeleteDataHandler;
use asylgrp\workbench\CommandBus\DeleteDataCommand;
use asylgrp\workbench\Storage\StorageInterface;
use asylgrp\workbench\Event\LogEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DeleteDataHandlerSpec extends ObjectBehavior
{
    function let(EventDispatcherInterface $dispatcher, StorageInterface $storage)
    {
        $this->setEventDispatcher($dispatcher);
        $this->setStorage($storage);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DeleteDataHandler::CLASS);
    }

    function it_removes_items_from_storage(DeleteDataCommand $command, $dispatcher, $storage)
    {
        $command->getKey()->willReturn('foobar');
        $storage->delete('foobar')->shouldBeCalled()->willReturn(true);
        $dispatcher->dispatch(LogEvent::INFO, Argument::type(LogEvent::CLASS))->shouldBeCalled();
        $this->handle($command);
    }

    function it_ignores_removing_unknown_items(DeleteDataCommand $command, $dispatcher, $storage)
    {
        $command->getKey()->willReturn('foobar');
        $storage->delete('foobar')->shouldBeCalled()->willReturn(false);
        $dispatcher->dispatch(LogEvent::INFO, Argument::type(LogEvent::CLASS))->shouldNotBeCalled();
        $this->handle($command);
    }
}
