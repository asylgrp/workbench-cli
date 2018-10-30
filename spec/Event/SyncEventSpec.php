<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\Event;

use asylgrp\workbench\Event\SyncEvent;
use Symfony\Component\EventDispatcher\Event;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SyncEventSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SyncEvent::CLASS);
    }

    function it_is_an_event()
    {
        $this->shouldHaveType(Event::CLASS);
    }
}
