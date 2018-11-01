<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Setup;

use asylgrp\matchmaker\Filter\FilterInterface;
use asylgrp\matchmaker\Filter\LogicalOrFilter;
use asylgrp\matchmaker\Filter\UnaccountedPreviousYearFilter;
use asylgrp\matchmaker\Filter\UnaccountedDateFilter;
use asylgrp\matchmaker\Filter\UnaccountedAmountFilter;
use byrokrat\amount\Currency\SEK;

final class BanFilterFactory
{
    public function createBanFilter(\DateTimeImmutable $date): FilterInterface
    {
        $filters = [];

        if ($date->format('m') > 2) {
            $filters[] = new UnaccountedPreviousYearFilter;
        }

        $filters[] = new UnaccountedDateFilter($date->modify('-3 month'));
        $filters[] = new UnaccountedAmountFilter(new SEK('20000'));

        return new LogicalOrFilter(...$filters);
    }
}
