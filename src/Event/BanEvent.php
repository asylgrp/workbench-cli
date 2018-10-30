<?php

declare(strict_types = 1);

namespace asylgrp\workbench\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * A ban event is dispatched each time a contact person is banned
 */
class BanEvent extends Event
{
    /**
     * Event id to ban a contact person
     */
    const BAN = 'ban.ban';

    /**
     * Event id to unban a contact person
     */
    const UNBAN = 'ban.unban';

    /**
     * @var string
     */
    private $contactId;

    /**
     * @var string
     */
    private $description;

    /**
     * @var bool
     */
    private $syncFlag;

    public function __construct(string $contactId, string $description, bool $syncFlag)
    {
        $this->contactId = $contactId;
        $this->description = $description;
        $this->syncFlag = $syncFlag;
    }

    public function getContactId(): string
    {
        return $this->contactId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function impliesServerSync(): bool
    {
        return $this->syncFlag;
    }
}
