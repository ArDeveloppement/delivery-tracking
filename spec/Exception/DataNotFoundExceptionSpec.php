<?php

namespace spec\ArDev\DeliveryTracking\Exception;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DataNotFoundExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('ArDev\DeliveryTracking\Exception\DataNotFoundException');
        $this->shouldBeAnInstanceOf('\Exception');
    }

    function it_should_display_an_error_message_with_adapter_name()
    {
        $this->beConstructedWith('adapterName');

        $this->getMessage()->shouldBeString();
        $this->getMessage()->shouldEqual('The requested data has not been found by this adapter (adapterName).');
    }

    function it_should_display_an_error_message_without_adapter_name()
    {
        $this->beConstructedWith();

        $this->getMessage()->shouldBeString();
        $this->getMessage()->shouldEqual('The requested data has not been found by this adapter.');
    }
}
