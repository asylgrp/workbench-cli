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
            'Symfony\\Component\\Console\\Application' => 'getApplicationService',
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
            'League\\Tactician\\CommandBus' => true,
            'League\\Tactician\\Setup\\QuickStart' => true,
            'Psr\\Container\\ContainerInterface' => true,
            'Symfony\\Component\\DependencyInjection\\ContainerInterface' => true,
            'Symfony\\Component\\EventDispatcher\\EventDispatcherInterface' => true,
            'asylgrp\\matchmaker\\Filter\\FilterInterface' => true,
            'asylgrp\\matchmaker\\MatchMaker' => true,
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
            'asylgrp\\workbench\\Console\\AnalyzeCommand' => true,
            'asylgrp\\workbench\\Console\\BookCommand' => true,
            'asylgrp\\workbench\\Console\\ImportCommand' => true,
            'asylgrp\\workbench\\Console\\InitCommand' => true,
            'asylgrp\\workbench\\Console\\StoreCommand' => true,
            'asylgrp\\workbench\\DependencyInjection\\ProjectServiceContainer' => true,
            'asylgrp\\workbench\\Event\\LogEvent' => true,
            'asylgrp\\workbench\\LogSubscriber' => true,
            'asylgrp\\workbench\\Setup\\BanFilterFactory' => true,
            'asylgrp\\workbench\\Setup\\MatchMakerFactory' => true,
            'asylgrp\\workbench\\Storage\\FlysystemStorage' => true,
            'asylgrp\\workbench\\Storage\\StorageInterface' => true,
            'asylgrp\\workbench\\TableBuilder' => true,
            'asylgrp\\workbench\\Utils\\SystemClock' => true,
            'byrokrat\\accounting\\Processor\\ProcessorInterface' => true,
            'byrokrat\\accounting\\Sie4\\Parser\\Sie4Parser' => true,
            'byrokrat\\accounting\\Sie4\\Parser\\Sie4ParserFactory' => true,
            'flysystem' => true,
            'flysystem_adapter' => true,
            'now' => true,
        );
    }

    /**
     * Gets the public 'Symfony\Component\Console\Application' shared autowired service.
     *
     * @return \Symfony\Component\Console\Application
     */
    protected function getApplicationService()
    {
        $this->services['Symfony\Component\Console\Application'] = $instance = new \Symfony\Component\Console\Application('Workbench', '$app_version$');

        $a = new \asylgrp\workbench\Console\AnalyzeCommand((new \asylgrp\workbench\Setup\MatchMakerFactory())->createMatchMaker(), (new \asylgrp\workbench\Setup\BanFilterFactory())->createBanFilter((new \asylgrp\workbench\Utils\SystemClock())->now()));

        $b = ($this->privates['League\Tactician\CommandBus'] ?? $this->getCommandBusService());
        $c = ($this->privates['Symfony\Component\EventDispatcher\EventDispatcherInterface'] ?? $this->privates['Symfony\Component\EventDispatcher\EventDispatcherInterface'] = new \Symfony\Component\EventDispatcher\EventDispatcher());
        $d = ($this->privates['asylgrp\workbench\Storage\StorageInterface'] ?? $this->getStorageInterfaceService());

        $a->setCommandBus($b);
        $a->setEventDispatcher($c);
        $a->setStorage($d);

        $f = new \asylgrp\workbench\AccountRenderer\LedgerRenderer();
        $g = new \asylgrp\workbench\TableBuilder();

        $f->setTableBuilder($g);

        $h = new \asylgrp\workbench\AccountRenderer\ListRenderer();
        $h->setTableBuilder($g);

        $i = new \asylgrp\workbench\AccountRenderer\MailRenderer();
        $i->setTableBuilder($g);
        $e = new \asylgrp\workbench\Console\BookCommand(new \asylgrp\workbench\AccountRenderer\AccountRendererContainer(array('ledger' => $f, 'list' => $h, 'mail' => $i)));

        $e->setCommandBus($b);
        $e->setEventDispatcher($c);
        $e->setStorage($d);

        $j = new \asylgrp\workbench\Console\ImportCommand();

        $j->setCommandBus($b);
        $j->setEventDispatcher($c);

        $k = new \asylgrp\workbench\Console\InitCommand();

        $k->setCommandBus($b);
        $k->setEventDispatcher($c);
        $k->setStorage($d);

        $l = new \asylgrp\workbench\Console\StoreCommand();

        $l->setCommandBus($b);
        $l->setEventDispatcher($c);
        $l->setStorage($d);
        $l->setTableBuilder($g);

        $instance->add($a);
        $instance->add($e);
        $instance->add($j);
        $instance->add($k);
        $instance->add($l);

        return $instance;
    }

    /**
     * Gets the private 'League\Tactician\CommandBus' shared autowired service.
     *
     * @return \League\Tactician\CommandBus
     */
    protected function getCommandBusService()
    {
        $a = new \asylgrp\workbench\CommandBus\PersistDataHandler();
        $b = ($this->privates['Symfony\Component\EventDispatcher\EventDispatcherInterface'] ?? $this->privates['Symfony\Component\EventDispatcher\EventDispatcherInterface'] = new \Symfony\Component\EventDispatcher\EventDispatcher());
        $c = ($this->privates['asylgrp\workbench\Storage\StorageInterface'] ?? $this->getStorageInterfaceService());

        $a->setEventDispatcher($b);
        $a->setStorage($c);

        $d = new \asylgrp\workbench\CommandBus\DeleteDataHandler();
        $d->setEventDispatcher($b);
        $d->setStorage($c);

        $e = new \asylgrp\workbench\CommandBus\ImportSie4Handler((new \byrokrat\accounting\Sie4\Parser\Sie4ParserFactory())->createParser(), new \byrokrat\accounting\Processor\TransactionProcessor());

        $this->privates['League\Tactician\CommandBus'] = $instance = (new \League\Tactician\Setup\QuickStart())->create(array('asylgrp\\workbench\\CommandBus\\PersistDataCommand' => $a, 'asylgrp\\workbench\\CommandBus\\DeleteDataCommand' => $d, 'asylgrp\\workbench\\CommandBus\\ImportSie4Command' => $e));

        $e->setCommandBus($instance);
        $e->setStorage($c);

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
            'app.name' => 'Workbench',
            'app.version' => '$app_version$',
            'env(WORKBENCH_PATH)' => 'workbench',
        );
    }
}
