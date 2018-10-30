<?php

declare(strict_types = 1);

namespace asylgrp\workbench\AccountRenderer;

/**
 * Create account renderer based on identifier
 */
class AccountRendererFactory
{
    /**
     * Identifies the ledger renderer
     */
    const FORMAT_LEDGER = 'ledger';

    /**
     * Identifies the list renderer
     */
    const FORMAT_LIST = 'list';

    /**
     * Identifies the mail renderer
     */
    const FORMAT_MAIL = 'mail';

    /**
     * Descriptive list of valid format indetifiers
     */
    const VALID_FORMATS = self::FORMAT_LEDGER . ', ' . self::FORMAT_LIST . ' or ' . self::FORMAT_MAIL;

    /**
     * Create renderer based on identifier
     *
     * @throws \Exception If format is unknown
     */
    public function createAccountRenderer(string $format): AccountRendererInterface
    {
        switch ($format) {
            case self::FORMAT_LEDGER:
                return new LedgerRenderer;
            case self::FORMAT_LIST:
                return new ListRenderer;
            case self::FORMAT_MAIL:
                return new MailRenderer;
            default:
                throw new \Exception("Unknown format: $format");
        }
    }
}
