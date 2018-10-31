<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\CommandBus;

use asylgrp\workbench\CommandBus\ImportSie4Command;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ImportSie4CommandSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('foo', 'bar');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ImportSie4Command::CLASS);
    }

    function it_contains_a_filename()
    {
        $this->getFilename()->shouldBeLike('foo');
    }

    function it_contains_contents()
    {
        $this->getContents()->shouldBeLike('bar');
    }
}
