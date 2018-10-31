<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\CommandBus;

use asylgrp\workbench\CommandBus\DeleteDataCommand;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DeleteDataCommandSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('key');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DeleteDataCommand::CLASS);
    }

    function it_contains_a_storage_key()
    {
        $this->getKey()->shouldBeLike('key');
    }
}
