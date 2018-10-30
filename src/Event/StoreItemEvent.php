<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Dispatched when an item is written to internal storage
 */
class StoreItemEvent extends RemoveItemEvent
{
    const NAME = 'item.store';

    /**
     * @var mixed
     */
    private $value;

    public function __construct(string $key, $value)
    {
        parent::__construct($key);
        $this->value = $value;
    }

    /**
     * Get item value
     */
    public function getValue()
    {
        return $this->value;
    }
}
