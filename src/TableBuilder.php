<?php

declare(strict_types = 1);

namespace asylgrp\workbench;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Helper\TableStyle;

/**
 * Facade around the symfony table helper
 */
class TableBuilder
{
    /**
     * Cell content should be right aligned
     */
    const ALIGN_RIGHT = 'r';

    /**
     * Cell content should be left aligned
     */
    const ALIGN_LEFT = 'l';

    /**
     * @var array Column definitions
     */
    private $columns = [];

    /**
     * @var string[] Optional table headers
     */
    private $headers = [];

    /**
     * @var array Table content
     */
    private $rows = [];

    public function reset()
    {
        $this->columns = [];
        $this->headers = [];
        $this->resetRows();
    }

    public function resetRows()
    {
        $this->rows = [];
    }

    public function addHeader(string $header)
    {
        $this->headers[] = $header;
    }

    public function addColumn(string $header, int $width = 0, string $align = self::ALIGN_LEFT)
    {
        $this->columns[] = [$header, $width, $align];
    }

    public function addRow(array $row)
    {
        $this->rows[] = $row;
    }

    public function addSeparator()
    {
        $this->rows[] = new TableSeparator();
    }

    public function buildTable(OutputInterface $output): Table
    {
        $table = new Table($output);

        $tableHeaders = [];

        foreach ($this->headers as $header) {
            $tableHeaders[] = [new TableCell($header, ['colspan' => count($this->columns)])];
        }

        $rightAligned = new TableStyle();
        $rightAligned->setPadType(STR_PAD_LEFT);

        $colHeaders = [];

        foreach ($this->columns as $index => list($header, $width, $align)) {
            $colHeaders[] = $header;

            $table->setColumnWidth($index, $width);

            if (self::ALIGN_RIGHT == $align) {
                $table->setColumnStyle($index, $rightAligned);
            }
        }

        $tableHeaders[] = $colHeaders;

        $table->setHeaders($tableHeaders);

        foreach ($this->rows as $row) {
            $table->addRow($row);
        }

        return $table;
    }
}
