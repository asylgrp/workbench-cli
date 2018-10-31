<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Storage;

use League\Flysystem\FilesystemInterface;

final class FlysystemStorage implements StorageInterface
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function has(string $key): bool
    {
        return $this->filesystem->has($key);
    }

    public function read(string  $key)
    {
        if (!$this->has($key)) {
            return null;
        }

        return unserialize($this->filesystem->read($key));
    }

    public function write(string $key, $object): bool
    {
        return $this->filesystem->put($key, serialize($object));
    }

    public function delete(string  $key): bool
    {
        if (!$this->has($key)) {
            return false;
        }

        return $this->filesystem->delete($key);
    }

    public function getKeys(): array
    {
        $keys = [];

        foreach ($this->filesystem->listContents() as $object) {
            if ('file' == $object['type']) {
                $keys[] = $object['basename'];
            }
        }

        return $keys;
    }
}
