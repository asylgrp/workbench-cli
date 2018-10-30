<?php

declare(strict_types = 1);

namespace asylgrp\workbench\AccountRenderer;

use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractAccountRenderer implements AccountRendererInterface
{
    /**
     * @var OutputInterface
     */
    private $output;

    public function initialize(string $header, OutputInterface $output)
    {
        $this->output = $output;
    }

    public function finalize()
    {
    }

    protected function getOutput(): OutputInterface
    {
        return $this->output;
    }
}
