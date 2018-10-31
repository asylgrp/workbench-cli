<?php

declare(strict_types = 1);

namespace asylgrp\workbench\AccountRenderer;

final class AccountRendererContainer
{
    /**
     * @var AccountRendererInterface[]
     */
    private $renderers;

    /**
     * @param AccountRendererInterface[] $renderers
     */
    public function __construct(array $renderers)
    {
        $this->renderers = $renderers;
    }

    public function getAccountRenderer(string $name): AccountRendererInterface
    {
        if (!isset($this->renderers[$name])) {
            throw new \RuntimeException("Renderer $name does not exist");
        }

        return $this->renderers[$name];
    }

    public function getAccountRendererNames(): string
    {
        return '"' . implode('" / "', array_keys($this->renderers)) . '"';
    }
}
