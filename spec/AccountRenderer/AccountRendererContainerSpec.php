<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\AccountRenderer;

use asylgrp\workbench\AccountRenderer\AccountRendererContainer;
use asylgrp\workbench\AccountRenderer\AccountRendererInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AccountRendererContainerSpec extends ObjectBehavior
{
    function let(AccountRendererInterface $renderer)
    {
        $this->beConstructedWith(['key' => $renderer]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AccountRendererContainer::CLASS);
    }

    function it_can_create_renderer_names()
    {
        $this->getAccountRendererNames()->shouldBeLike('"key"');
    }

    function it_fails_on_unknown_format()
    {
        $this->shouldThrow('\RuntimeException')->duringgetAccountRenderer('this-is-not-a-valid-format');
    }

    function it_can_fetch_renderers($renderer)
    {
        $this->getAccountRenderer('key')->shouldReturn($renderer);
    }
}
