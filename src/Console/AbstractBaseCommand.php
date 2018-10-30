<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Console;

use asylgrp\workbench\Config;
use asylgrp\workbench\LogSubscriber;
use asylgrp\workbench\Event\LogEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Aura\Di\Container;
use Aura\Di\ContainerBuilder;

/**
 * A cli command that have access to the dependency injection container
 */
abstract class AbstractBaseCommand extends Command
{
    /**
     * @var Container
     */
    private $container;

    /**
     * Configure the config option
     */
    protected function configure()
    {
        $this->addOption('config', 'c', InputOption::VALUE_REQUIRED, 'Path to configuration directory');
    }

    /**
     * Setup the dependency injection container
     *
     * @throws \Exception If configure() has not been called
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if (!$input->hasOption('config')) {
            throw new \Exception('Command not constructed properly, did you call parent::configure()?');
        }

        $configPath = (new ConfigPathLocator)->locateConfigPath(
            (string)$input->getOption('config'),
            (string)getenv('WORKBENCH_PATH')
        );

        $this->container = (new ContainerBuilder)->newConfiguredInstance([new Config($configPath)]);

        $this->registerEventListeners($this->getContainer()->get('event_dispatcher'), $input, $output);

        $this->dispatch(
            LogEvent::DEBUG,
            new LogEvent("Using configuration directory <info>$configPath</info>")
        );
    }

    /**
     * Register event listeners that may not be common to all commands
     */
    protected function registerEventListeners(
        EventDispatcherInterface $dispatcher,
        InputInterface $input,
        OutputInterface $output
    ) {
        $dispatcher->addSubscriber(new LogSubscriber($output));
    }

    /**
     * @throws \Exception If initialize() has not been called
     */
    protected function getContainer(): Container
    {
        if (!isset($this->container)) {
            throw new \Exception('Command not constructed properly, did you call parent::initialize()?');
        }

        return $this->container;
    }

    /**
     * Heler to simplify the dispatching of events
     */
    protected function dispatch(string $name, Event $event)
    {
        $this->getContainer()->get('event_dispatcher')->dispatch($name, $event);
    }
}
