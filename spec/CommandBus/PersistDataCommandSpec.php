<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\CommandBus;

use asylgrp\workbench\CommandBus\PersistDataCommand;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PersistDataCommandSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('key', 'value');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PersistDataCommand::CLASS);
    }

    function it_contains_a_storage_key()
    {
        $this->getKey()->shouldBeLike('key');
    }

    function it_contains_a_value()
    {
        $this->getValue()->shouldBeLike('value');
    }
}
