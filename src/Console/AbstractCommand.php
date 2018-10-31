<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Console;

use asylgrp\workbench\DependencyInjection\CommandBusProperty;
use asylgrp\workbench\DependencyInjection\DispatcherProperty;
use asylgrp\workbench\LogSubscriber;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    use CommandBusProperty, DispatcherProperty;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->dispatcher->addSubscriber(new LogSubscriber($output));
    }
}
