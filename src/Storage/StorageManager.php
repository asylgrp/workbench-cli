<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Storage;

use League\Flysystem\FilesystemInterface;

/**
 * Manage local storage
 */
class StorageManager
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var string Prefix used when accessing files in filesystem
     */
    private $prefix;

    public function __construct(FilesystemInterface $filesystem, string $prefix = '')
    {
        $this->filesystem = $filesystem;
        $this->prefix = $prefix;
    }

    /**
     * Check if object exists in storage
     */
    public function has(string $key): bool
    {
        return $this->filesystem->has("{$this->prefix}/$key");
    }

    /**
     * Read data from storage
     *
     * @param  string $key Id of data to read
     * @return mixed  Whatever stored, or null
     */
    public function read(string  $key)
    {
        if (!$this->has($key)) {
            return null;
        }

        return unserialize($this->filesystem->read("{$this->prefix}/$key"));
    }

    /**
     * Write object to storage
     *
     * @param string $key
     * @param mixed  $object
     */
    public function write(string $key, $object)
    {
        $this->filesystem->put("{$this->prefix}/$key", serialize($object));
    }

    /**
     * Delete data from storage
     *
     * @param  string $key Id of data to delete
     * @return bool   True if item was deleted, false if not
     */
    public function delete(string  $key): bool
    {
        if (!$this->has($key)) {
            return false;
        }

        $this->filesystem->delete("{$this->prefix}/$key");
        return true;
    }

    /**
     * Get list of keys currently in storage
     *
     * @return string[]
     */
    public function getKeys(): array
    {
        $keys = [];

        foreach ($this->filesystem->listContents($this->prefix) as $object) {
            if ('file' == $object['type']) {
                $keys[] = $object['basename'];
            }
        }

        return $keys;
    }
}
