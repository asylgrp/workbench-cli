<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\Sync;

use asylgrp\workbench\Sync\SyncAction;
use asylgrp\workbench\Event\SyncEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SyncActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SyncAction::CLASS);
    }
}
