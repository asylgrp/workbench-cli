<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\Ban;

use asylgrp\workbench\Ban\BanAction;
use asylgrp\workbench\Event\BanEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BanActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BanAction::CLASS);
    }
}
