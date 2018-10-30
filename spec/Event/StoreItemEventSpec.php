<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\Event;

use asylgrp\workbench\Event\StoreItemEvent;
use Symfony\Component\EventDispatcher\Event;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StoreItemEventSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('foo', 'bar');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StoreItemEvent::CLASS);
    }

    function it_is_an_event()
    {
        $this->shouldHaveType(Event::CLASS);
    }

    function it_contains_a_storage_key()
    {
        $this->getKey()->shouldBeLike('foo');
    }

    function it_contains_a_value()
    {
        $this->getValue()->shouldBeLike('bar');
    }
}
