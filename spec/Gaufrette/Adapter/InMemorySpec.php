<?php

namespace spec\Gaufrette\Adapter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InMemorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Gaufrette\Adapter\InMemory');
    }

    function it_set_file_content()
    {
        $this->setFile('test', array('content' => 'the_test'));

        $this->readContent('test')->shouldReturn('the_test');
    }

    function it_set_file_metadata()
    {
        $this->setFile('test', array('metadata' => array('m1' => 'the_metadata' )));

        $this->readMetadata('test')->shouldReturn(array('m1' => 'the_metadata'));
    }

    function it_deduce_mime_type()
    {
        $image = file_get_contents(sprintf('%s/../../../fixtures/image.gif', __DIR__));

        $this->setFile('text', array('content' => 'the_test'));
        $this->setFile('image', array('content' => $image));

        $this->readMimeType('text')->shouldReturn('text/plain');
        $this->readMimeType('image')->shouldReturn('image/gif');
    }

    function it_deduce_size()
    {
        $image = file_get_contents(sprintf('%s/../../../fixtures/image.gif', __DIR__));

        $this->setFile('text', array('content' => 'the_test'));
        $this->setFile('image', array('content' => $image));

        $this->readSize('text')->shouldReturn(8);
        $this->readSize('image')->shouldReturn(907);
    }

    function it_list_files()
    {
        $this->setFile('text', array('content' => 'the_test'));
        $this->setFile('image', array('content' => 'the_image'));

        $this->listKeys()->shouldReturn(array('image', 'text'));
        $this->listKeys('te')->shouldReturn(array('text'));
    }
}
