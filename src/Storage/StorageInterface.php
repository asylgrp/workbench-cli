<?php

namespace asylgrp\workbench\Storage;

interface StorageInterface
{
    /**
     * Check if object exists in storage
     */
    public function has(string $key): bool;

    /**
     * Read data from storage
     *
     * @param  string $key Id of data to read
     * @return mixed  Whatever stored
     */
    public function read(string  $key);

    /**
     * Write object to storage
     *
     * @param  string $key Id of data to write
     * @param  mixed  $value
     * @return bool   True if item was written, false if not
     */
    public function write(string $key, $value): bool;

    /**
     * Delete data from storage
     *
     * @param  string $key Id of data to delete
     * @return bool   True if item was deleted, false if not
     */
    public function delete(string  $key): bool;

    /**
     * Get list of keys currently in storage
     *
     * @return string[]
     */
    public function getKeys(): array;
}
