<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench;

use asylgrp\workbench\LogSubscriber;
use asylgrp\workbench\Event\LogEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LogSubscriberSpec extends ObjectBehavior
{
    function let(OutputInterface $output)
    {
        $this->beConstructedWith($output);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LogSubscriber::CLASS);
    }

    function it_is_an_event_subscriber()
    {
        $this->shouldHaveType(EventSubscriberInterface::CLASS);
    }

    function it_can_subscribe_to_events()
    {
        $this->getSubscribedEvents()->shouldBeLike([
            LogEvent::INFO => 'onInfo',
            LogEvent::DEBUG => 'onDebug'
        ]);
    }

    function it_writes_info_messages_to_output(LogEvent $event, $output)
    {
        $event->getMessage()->willReturn('foobar');
        $output->writeln('foobar')->shouldBeCalled();
        $this->onInfo($event);
    }

    function it_writes_debug_messages_to_output_if_verbose(LogEvent $event, $output)
    {
        $event->getMessage()->willReturn('foobar');
        $output->isVerbose()->willReturn(true);
        $output->writeln('foobar')->shouldBeCalled();
        $this->onDebug($event);
    }

    function it_does_not_write_debug_messages_to_output_if_not_verbose(LogEvent $event, $output)
    {
        $event->getMessage()->willReturn('foobar');
        $output->isVerbose()->willReturn(false);
        $output->writeln('foobar')->shouldNotBeCalled();
        $this->onDebug($event);
    }
}
