<?php

declare(strict_types = 1);

namespace asylgrp\workbench;

/**
 * Handles a refenrence to a table builder
 */
trait TableBuilderTrait
{
    /**
     * @var TableBuilder
     */
    private $tableBuilder;

    public function setTableBuilder(TableBuilder $tableBuilder)
    {
        $this->tableBuilder = $tableBuilder;
    }

    protected function getTableBuilder(): TableBuilder
    {
        if (is_null($this->tableBuilder)) {
            $this->tableBuilder = new TableBuilder;
        }

        return $this->tableBuilder;
    }
}
