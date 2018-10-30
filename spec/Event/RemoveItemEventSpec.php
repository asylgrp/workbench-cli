<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\Event;

use asylgrp\workbench\Event\RemoveItemEvent;
use Symfony\Component\EventDispatcher\Event;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RemoveItemEventSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('foobar');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RemoveItemEvent::CLASS);
    }

    function it_is_an_event()
    {
        $this->shouldHaveType(Event::CLASS);
    }

    function it_contains_a_storage_key()
    {
        $this->getKey()->shouldBeLike('foobar');
    }
}
