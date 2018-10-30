<?php

declare(strict_types = 1);

namespace asylgrp\workbench;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;

/**
 * Dependency injection configurations
 */
class Config extends ContainerConfig
{
    /**
     * @var string Path to configuration directory
     */
    private $configPath;

    /**
     * @param string $configPath Path to configuration directory
     */
    public function __construct(string $configPath)
    {
        $this->configPath = $configPath;
    }

    public function define(Container $di)
    {
        $di->set('filesystem', $di->lazyNew(
            'League\Flysystem\Filesystem',
            [$di->lazyNew('League\Flysystem\Adapter\Local', [$this->configPath])]
        ));

        $di->params[Storage\StorageManager::CLASS]['filesystem'] = $di->lazyGet('filesystem');
        $di->params[Storage\StorageManager::CLASS]['prefix'] = 'storage';
        $di->set('storage_manager', $di->lazyNew(Storage\StorageManager::CLASS));

        $di->set('event_dispatcher', $di->lazy(function () use ($di) {
            $dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher;

            $dispatcher->addSubscriber(new Storage\StorageSubscriber($di->get('storage_manager')));

            $dispatcher->addListener(
                Event\ImportEvent::SIE,
                new Import\ImportSieAction(
                    (new \byrokrat\accounting\Sie4\Parser\Sie4ParserFactory)->createParser(),
                    $di->get('storage_manager')
                )
            );

            $dispatcher->addListener(Event\BanEvent::BAN, new Ban\BanAction);
            $dispatcher->addListener(Event\BanEvent::UNBAN, new Ban\BanAction);

            $dispatcher->addListener(Event\SyncEvent::NAME, new Sync\SyncAction);

            return $dispatcher;
        }));
    }
}
