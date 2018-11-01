<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Setup;

use asylgrp\matchmaker\MatchMaker;
use asylgrp\matchmaker\Matcher\RelatedMatcher;
use asylgrp\matchmaker\Matcher\ZeroAmountMatcher;
use asylgrp\matchmaker\Matcher\DateAndAmountMatcher;
use asylgrp\matchmaker\Matcher\GroupingMatcher;
use asylgrp\matchmaker\Matcher\AmountMatcher;
use asylgrp\matchmaker\Matcher\DateMatcher;
use asylgrp\matchmaker\Matcher\SingleMatcher;
use asylgrp\matchmaker\Matcher\DateComparator;
use asylgrp\matchmaker\Matcher\AmountComparator;

final class MatchMakerFactory
{
    public function createMatchMaker(): MatchMaker
    {
        $max10days = new DateComparator(10);
        $max30days = new DateComparator(30);
        $max0percent = new AmountComparator(0.0);
        $max5percent = new AmountComparator(0.05);

        return new MatchMaker(
            new RelatedMatcher,
            new ZeroAmountMatcher,
            new DateAndAmountMatcher($max10days, $max0percent),
            new DateAndAmountMatcher($max10days, $max5percent),
            new GroupingMatcher($max10days, $max0percent),
            new GroupingMatcher($max30days, $max5percent),
            new AmountMatcher($max5percent),
            new DateMatcher($max10days),
            new SingleMatcher
        );
    }
}
