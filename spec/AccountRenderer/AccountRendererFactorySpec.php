<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\AccountRenderer;

use asylgrp\workbench\AccountRenderer\AccountRendererFactory;
use asylgrp\workbench\AccountRenderer\LedgerRenderer;
use asylgrp\workbench\AccountRenderer\ListRenderer;
use asylgrp\workbench\AccountRenderer\MailRenderer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AccountRendererFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(AccountRendererFactory::CLASS);
    }

    function it_fails_on_unknown_format()
    {
        $this->shouldThrow('\Exception')->duringCreateAccountRenderer('this-is-not-a-valid-format');
    }

    function it_can_create_a_ledger_renderer()
    {
        $this->createAccountRenderer(AccountRendererFactory::FORMAT_LEDGER)->shouldHaveType(LedgerRenderer::CLASS);
    }

    function it_can_create_a_list_renderer()
    {
        $this->createAccountRenderer(AccountRendererFactory::FORMAT_LIST)->shouldHaveType(ListRenderer::CLASS);
    }

    function it_can_create_a_mail_renderer()
    {
        $this->createAccountRenderer(AccountRendererFactory::FORMAT_MAIL)->shouldHaveType(MailRenderer::CLASS);
    }
}
