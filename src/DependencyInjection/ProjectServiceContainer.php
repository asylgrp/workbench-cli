<?php

namespace asylgrp\workbench\DependencyInjection;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;

/**
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 *
 * @final since Symfony 3.3
 */
class ProjectServiceContainer extends Container
{
    private $parameters;
    private $targetDirs = array();

    /**
     * @internal but protected for BC on cache:clear
     */
    protected $privates = array();

    public function __construct()
    {
        $this->parameters = $this->getDefaultParameters();

        $this->services = $this->privates = array();
        $this->methodMap = array(
            'League\\Tactician\\CommandBus' => 'getCommandBusService',
            'asylgrp\\workbench\\Console\\BookCommand' => 'getBookCommandService',
            'asylgrp\\workbench\\Console\\ImportCommand' => 'getImportCommandService',
            'asylgrp\\workbench\\Console\\InitCommand' => 'getInitCommandService',
            'asylgrp\\workbench\\Console\\StoreCommand' => 'getStoreCommandService',
        );

        $this->aliases = array();
    }

    public function reset()
    {
        $this->privates = array();
        parent::reset();
    }

    public function compile()
    {
        throw new LogicException('You cannot compile a dumped container that was already compiled.');
    }

    public function isCompiled()
    {
        return true;
    }

    public function getRemovedIds()
    {
        return array(
            'League\\Tactician\\Setup\\QuickStart' => true,
            'Psr\\Container\\ContainerInterface' => true,
            'Symfony\\Component\\DependencyInjection\\ContainerInterface' => true,
            'Symfony\\Component\\EventDispatcher\\EventDispatcherInterface' => true,
            'asylgrp\\workbench\\AccountRenderer\\AccountRendererContainer' => true,
            'asylgrp\\workbench\\AccountRenderer\\LedgerRenderer' => true,
            'asylgrp\\workbench\\AccountRenderer\\ListRenderer' => true,
            'asylgrp\\workbench\\AccountRenderer\\MailRenderer' => true,
            'asylgrp\\workbench\\CommandBus\\DeleteDataCommand' => true,
            'asylgrp\\workbench\\CommandBus\\DeleteDataHandler' => true,
            'asylgrp\\workbench\\CommandBus\\ImportSie4Command' => true,
            'asylgrp\\workbench\\CommandBus\\ImportSie4Handler' => true,
            'asylgrp\\workbench\\CommandBus\\PersistDataCommand' => true,
            'asylgrp\\workbench\\CommandBus\\PersistDataHandler' => true,
            'asylgrp\\workbench\\Storage\\StorageInterface' => true,
            'asylgrp\\workbench\\TableBuilder' => true,
            'byrokrat\\accounting\\Processor\\ProcessorInterface' => true,
            'byrokrat\\accounting\\Sie4\\Parser\\Sie4Parser' => true,
            'byrokrat\\accounting\\Sie4\\Parser\\Sie4ParserFactory' => true,
            'flysystem' => true,
            'flysystem_adapter' => true,
        );
    }

    /**
     * Gets the public 'League\Tactician\CommandBus' shared autowired service.
     *
     * @return \League\Tactician\CommandBus
     */
    protected function getCommandBusService()
    {
        $a = new \asylgrp\workbench\CommandBus\ImportSie4Handler((new \byrokrat\accounting\Sie4\Parser\Sie4ParserFactory())->createParser(), new \byrokrat\accounting\Processor\TransactionProcessor());

        $b = ($this->privates['asylgrp\workbench\Storage\StorageInterface'] ?? $this->getStorageInterfaceService());

        $c = new \asylgrp\workbench\CommandBus\PersistDataHandler();
        $d = ($this->privates['Symfony\Component\EventDispatcher\EventDispatcherInterface'] ?? $this->privates['Symfony\Component\EventDispatcher\EventDispatcherInterface'] = new \Symfony\Component\EventDispatcher\EventDispatcher());

        $c->setEventDispatcher($d);
        $c->setStorage($b);

        $e = new \asylgrp\workbench\CommandBus\DeleteDataHandler();
        $e->setEventDispatcher($d);
        $e->setStorage($b);

        $this->services['League\Tactician\CommandBus'] = $instance = (new \League\Tactician\Setup\QuickStart())->create(array('asylgrp\\workbench\\CommandBus\\ImportSie4Command' => $a, 'asylgrp\\workbench\\CommandBus\\PersistDataCommand' => $c, 'asylgrp\\workbench\\CommandBus\\DeleteDataCommand' => $e));

        $a->setCommandBus($instance);
        $a->setStorage($b);

        return $instance;
    }

    /**
     * Gets the public 'asylgrp\workbench\Console\BookCommand' shared autowired service.
     *
     * @return \asylgrp\workbench\Console\BookCommand
     */
    protected function getBookCommandService()
    {
        $a = new \asylgrp\workbench\AccountRenderer\LedgerRenderer();
        $b = ($this->privates['asylgrp\workbench\TableBuilder'] ?? $this->privates['asylgrp\workbench\TableBuilder'] = new \asylgrp\workbench\TableBuilder());

        $a->setTableBuilder($b);

        $c = new \asylgrp\workbench\AccountRenderer\ListRenderer();
        $c->setTableBuilder($b);

        $d = new \asylgrp\workbench\AccountRenderer\MailRenderer();
        $d->setTableBuilder($b);

        $this->services['asylgrp\workbench\Console\BookCommand'] = $instance = new \asylgrp\workbench\Console\BookCommand(new \asylgrp\workbench\AccountRenderer\AccountRendererContainer(array('ledger' => $a, 'list' => $c, 'mail' => $d)));

        $instance->setCommandBus(($this->services['League\Tactician\CommandBus'] ?? $this->getCommandBusService()));
        $instance->setEventDispatcher(($this->privates['Symfony\Component\EventDispatcher\EventDispatcherInterface'] ?? $this->privates['Symfony\Component\EventDispatcher\EventDispatcherInterface'] = new \Symfony\Component\EventDispatcher\EventDispatcher()));
        $instance->setStorage(($this->privates['asylgrp\workbench\Storage\StorageInterface'] ?? $this->getStorageInterfaceService()));

        return $instance;
    }

    /**
     * Gets the public 'asylgrp\workbench\Console\ImportCommand' shared autowired service.
     *
     * @return \asylgrp\workbench\Console\ImportCommand
     */
    protected function getImportCommandService()
    {
        $this->services['asylgrp\workbench\Console\ImportCommand'] = $instance = new \asylgrp\workbench\Console\ImportCommand();

        $instance->setCommandBus(($this->services['League\Tactician\CommandBus'] ?? $this->getCommandBusService()));
        $instance->setEventDispatcher(($this->privates['Symfony\Component\EventDispatcher\EventDispatcherInterface'] ?? $this->privates['Symfony\Component\EventDispatcher\EventDispatcherInterface'] = new \Symfony\Component\EventDispatcher\EventDispatcher()));

        return $instance;
    }

    /**
     * Gets the public 'asylgrp\workbench\Console\InitCommand' shared autowired service.
     *
     * @return \asylgrp\workbench\Console\InitCommand
     */
    protected function getInitCommandService()
    {
        $this->services['asylgrp\workbench\Console\InitCommand'] = $instance = new \asylgrp\workbench\Console\InitCommand();

        $instance->setCommandBus(($this->services['League\Tactician\CommandBus'] ?? $this->getCommandBusService()));
        $instance->setEventDispatcher(($this->privates['Symfony\Component\EventDispatcher\EventDispatcherInterface'] ?? $this->privates['Symfony\Component\EventDispatcher\EventDispatcherInterface'] = new \Symfony\Component\EventDispatcher\EventDispatcher()));
        $instance->setStorage(($this->privates['asylgrp\workbench\Storage\StorageInterface'] ?? $this->getStorageInterfaceService()));

        return $instance;
    }

    /**
     * Gets the public 'asylgrp\workbench\Console\StoreCommand' shared autowired service.
     *
     * @return \asylgrp\workbench\Console\StoreCommand
     */
    protected function getStoreCommandService()
    {
        $this->services['asylgrp\workbench\Console\StoreCommand'] = $instance = new \asylgrp\workbench\Console\StoreCommand();

        $instance->setCommandBus(($this->services['League\Tactician\CommandBus'] ?? $this->getCommandBusService()));
        $instance->setEventDispatcher(($this->privates['Symfony\Component\EventDispatcher\EventDispatcherInterface'] ?? $this->privates['Symfony\Component\EventDispatcher\EventDispatcherInterface'] = new \Symfony\Component\EventDispatcher\EventDispatcher()));
        $instance->setStorage(($this->privates['asylgrp\workbench\Storage\StorageInterface'] ?? $this->getStorageInterfaceService()));
        $instance->setTableBuilder(($this->privates['asylgrp\workbench\TableBuilder'] ?? $this->privates['asylgrp\workbench\TableBuilder'] = new \asylgrp\workbench\TableBuilder()));

        return $instance;
    }

    /**
     * Gets the private 'asylgrp\workbench\Storage\StorageInterface' shared autowired service.
     *
     * @return \asylgrp\workbench\Storage\FlysystemStorage
     */
    protected function getStorageInterfaceService()
    {
        return $this->privates['asylgrp\workbench\Storage\StorageInterface'] = new \asylgrp\workbench\Storage\FlysystemStorage(new \League\Flysystem\Filesystem(new \League\Flysystem\Adapter\Local($this->getEnv('string:WORKBENCH_PATH').'/storage')));
    }

    public function getParameter($name)
    {
        $name = (string) $name;

        if (!(isset($this->parameters[$name]) || isset($this->loadedDynamicParameters[$name]) || array_key_exists($name, $this->parameters))) {
            throw new InvalidArgumentException(sprintf('The parameter "%s" must be defined.', $name));
        }
        if (isset($this->loadedDynamicParameters[$name])) {
            return $this->loadedDynamicParameters[$name] ? $this->dynamicParameters[$name] : $this->getDynamicParameter($name);
        }

        return $this->parameters[$name];
    }

    public function hasParameter($name)
    {
        $name = (string) $name;

        return isset($this->parameters[$name]) || isset($this->loadedDynamicParameters[$name]) || array_key_exists($name, $this->parameters);
    }

    public function setParameter($name, $value)
    {
        throw new LogicException('Impossible to call set() on a frozen ParameterBag.');
    }

    public function getParameterBag()
    {
        if (null === $this->parameterBag) {
            $parameters = $this->parameters;
            foreach ($this->loadedDynamicParameters as $name => $loaded) {
                $parameters[$name] = $loaded ? $this->dynamicParameters[$name] : $this->getDynamicParameter($name);
            }
            $this->parameterBag = new FrozenParameterBag($parameters);
        }

        return $this->parameterBag;
    }

    private $loadedDynamicParameters = array(
        'storage.path' => false,
    );
    private $dynamicParameters = array();

    /**
     * Computes a dynamic parameter.
     *
     * @param string The name of the dynamic parameter to load
     *
     * @return mixed The value of the dynamic parameter
     *
     * @throws InvalidArgumentException When the dynamic parameter does not exist
     */
    private function getDynamicParameter($name)
    {
        switch ($name) {
            case 'storage.path': $value = $this->getEnv('string:WORKBENCH_PATH').'/storage'; break;
            default: throw new InvalidArgumentException(sprintf('The dynamic parameter "%s" must be defined.', $name));
        }
        $this->loadedDynamicParameters[$name] = true;

        return $this->dynamicParameters[$name] = $value;
    }

    /**
     * Gets the default parameters.
     *
     * @return array An array of the default parameters
     */
    protected function getDefaultParameters()
    {
        return array(
            'env(WORKBENCH_PATH)' => 'workbench',
        );
    }
}
