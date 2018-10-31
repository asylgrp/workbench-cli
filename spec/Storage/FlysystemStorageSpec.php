<?php

declare(strict_types = 1);

namespace spec\asylgrp\workbench\Storage;

use asylgrp\workbench\Storage\FlysystemStorage;
use League\Flysystem\FilesystemInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FlysystemStorageSpec extends ObjectBehavior
{
    function let(FilesystemInterface $filesystem)
    {
        $this->beConstructedWith($filesystem);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FlysystemStorage::CLASS);
    }

    function it_can_check_if_object_exists($filesystem)
    {
        $filesystem->has('foobar')->willReturn(true);
        $this->has('foobar')->shouldBeLike(true);
    }

    function it_can_read_objects($filesystem)
    {
        $filesystem->has('foo')->willReturn(true);
        $filesystem->read('foo')->willReturn('s:3:"bar";');
        $this->read('foo')->shouldBeLike('bar');
    }

    function it_returns_null_if_object_does_not_exist($filesystem)
    {
        $filesystem->has('foo')->willReturn(false);
        $this->read('foo')->shouldBeLike(null);
    }

    function it_can_write_objects($filesystem)
    {
        $filesystem->put('foo', 's:3:"bar";')->willReturn(true)->shouldBeCalled();
        $this->write('foo', 'bar')->shouldReturn(true);
    }

    function it_can_delete_objects($filesystem)
    {
        $filesystem->has('foo')->willReturn(true);
        $filesystem->delete('foo')->willReturn(true)->shouldBeCalled();
        $this->delete('foo')->shouldBeLike(true);
    }

    function it_returns_false_if_deleted_object_does_not_exist($filesystem)
    {
        $filesystem->has('foo')->willReturn(false);
        $this->delete('foo')->shouldBeLike(false);
    }

    function it_can_list_objects($filesystem)
    {
        $objects = [
            [
                'type' => 'file',
                'basename' => 'foo'
            ],
            [
                'type' => 'file',
                'basename' => 'bar'
            ],
            [
                'type' => 'dir',
                'basename' => 'baz'
            ]
        ];
        $filesystem->listContents()->willReturn($objects);
        $this->getKeys()->shouldBeLike(['foo', 'bar']);
    }
}
