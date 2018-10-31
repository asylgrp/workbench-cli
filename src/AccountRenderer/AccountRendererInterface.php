<?php

declare(strict_types = 1);

namespace asylgrp\workbench\AccountRenderer;

use Symfony\Component\Console\Output\OutputInterface;
use byrokrat\accounting\Dimension\AccountInterface;

interface AccountRendererInterface
{
    public function initialize(string $header, OutputInterface $output): void;

    public function renderAccount(AccountInterface $account): void;

    public function finalize(): void;
}
