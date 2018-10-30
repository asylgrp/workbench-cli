<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Command;

/**
 * Rules for locating the config directory
 */
class ConfigPathLocator
{
    public function locateConfigPath(string $option, string $environment): string
    {
        if ($option) {
            return $option;
        }

        if ($environment) {
            return $environment;
        }

        $home = posix_getpwuid(posix_getuid())['dir'];

        return $home . DIRECTORY_SEPARATOR . '.workbench';
    }
}
