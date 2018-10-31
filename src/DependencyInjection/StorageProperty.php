<?php

declare(strict_types = 1);

namespace asylgrp\workbench\DependencyInjection;

use asylgrp\workbench\Storage\StorageInterface;

/**
 * Use this trait to automatically inject a storage
 */
trait StorageProperty
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @required
     */
    public function setStorage(StorageInterface $storage): void
    {
        $this->storage = $storage;
    }
}
