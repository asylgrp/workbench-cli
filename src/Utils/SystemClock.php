<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Utils;

class SystemClock
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable;
    }
}
