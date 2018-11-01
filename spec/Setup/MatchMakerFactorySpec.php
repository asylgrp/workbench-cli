<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\Setup;

use asylgrp\workbench\Setup\MatchMakerFactory;
use asylgrp\matchmaker\MatchMaker;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MatchMakerFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(MatchMakerFactory::CLASS);
    }

    function it_can_create_match_makers()
    {
        $this->createMatchMaker()->shouldHaveType(MatchMaker::CLASS);
    }
}
