<?php

declare(strict_types = 1);

namespace asylgrp\workbench;

use byrokrat\accounting\Query;

Query::macro('orderById', function () {
    return $this->orderBy(function ($left, $right) {
        return $left->getId() <=> $right->getId();
    });
});

Query::macro('whereUnbalanced', function () {
    return $this->where(function ($account) {
        return !$account->getAttribute('summary')->getOutgoingBalance()->isZero();
    });
});

Query::macro('whereUnused', function () {
    return $this->where(function ($account) {
        return count($account->getAttribute('transactions')) == 0
            && $account->getAttribute('incoming_balance')->isZero()
            && strpos($account->getDescription(), '--') !== 0;
    });
});

Query::macro('whereContactPerson', function () {
    return $this->filter(function ($account) {
        return (int)$account->getId() > 1500
            && (int)$account->getId() < 1700
            && strpos($account->getDescription(), '--') !== 0;
    });
});
