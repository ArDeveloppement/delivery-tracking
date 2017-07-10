<?php

namespace spec\ArDev\DeliveryTracking\Adapter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ChronopostAdapterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('ArDev\DeliveryTracking\Adapter\ChronopostAdapter');
    }
}
