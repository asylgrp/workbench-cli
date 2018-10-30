<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Dispatched when an item is removed from internal storage
 */
class RemoveItemEvent extends Event
{
    const NAME = 'item.remove';

    /**
     * @var string Storage key
     */
    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * Get Storage item key
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
