<?php

declare(strict_types = 1);

namespace asylgrp\workbench\DependencyInjection;

use asylgrp\workbench\TableBuilder;

/**
 * Use this trait to automatically inject a table builder
 */
trait TableBuilderProperty
{
    /**
     * @var TableBuilder
     */
    protected $tableBuilder;

    /**
     * @required
     */
    public function setTableBuilder(TableBuilder $tableBuilder)
    {
        $this->tableBuilder = $tableBuilder;
    }
}
