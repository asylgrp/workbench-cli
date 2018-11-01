<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\Setup;

use asylgrp\workbench\Setup\BanFilterFactory;
use asylgrp\matchmaker\Filter\FilterInterface;
use asylgrp\matchmaker\Filter\LogicalOrFilter;
use asylgrp\matchmaker\Filter\UnaccountedPreviousYearFilter;
use asylgrp\matchmaker\Filter\UnaccountedDateFilter;
use asylgrp\matchmaker\Filter\UnaccountedAmountFilter;
use byrokrat\amount\Currency\SEK;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BanFilterFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BanFilterFactory::CLASS);
    }

    function it_can_create_filter_without_previous_year()
    {
        $this->createBanFilter(new \DateTimeImmutable('20180228'))->shouldBeLike(
            new LogicalOrFilter(
                new UnaccountedDateFilter(new \DateTimeImmutable('20171128')),
                new UnaccountedAmountFilter(new SEK('20000'))
            )
        );
    }

    function it_can_create_filter_with_previous_year()
    {
        $this->createBanFilter(new \DateTimeImmutable('20181031'))->shouldBeLike(
            new LogicalOrFilter(
                new UnaccountedPreviousYearFilter,
                new UnaccountedDateFilter(new \DateTimeImmutable('20180731')),
                new UnaccountedAmountFilter(new SEK('20000'))
            )
        );
    }
}
