<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\Event;

use asylgrp\workbench\Event\BanEvent;
use Symfony\Component\EventDispatcher\Event;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BanEventSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('1234', 'description', true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BanEvent::CLASS);
    }

    function it_is_an_event()
    {
        $this->shouldHaveType(Event::CLASS);
    }

    function it_contains_a_contact_id()
    {
        $this->getContactId()->shouldBeLike('1234');
    }

    function it_contains_a_description()
    {
        $this->getDescription()->shouldBeLike('description');
    }

    function it_contains_a_sync_flag()
    {
        $this->impliesServerSync()->shouldBeLike(true);
    }
}
