<?php

namespace spec\ArDev\DeliveryTracking\Exception;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UnsupportedFeatureExceptionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('functionName', 'adapterName');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ArDev\DeliveryTracking\Exception\UnsupportedFeatureException');
        $this->shouldBeAnInstanceOf('\Exception');
    }

    function it_should_display_an_error_message_with_adapter_name()
    {
        $this->getMessage()->shouldBeString();
        $this->getMessage()->shouldEqual('The feature "functionName" is not supported by this adapter (adapterName).');
    }

    function it_should_display_an_error_message_without_adapter_name()
    {
        $this->beConstructedWith('functionName');

        $this->getMessage()->shouldBeString();
        $this->getMessage()->shouldEqual('The feature "functionName" is not supported by this adapter.');
    }
}
