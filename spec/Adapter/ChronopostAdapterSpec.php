<?php

namespace spec\CoSpirit\DeliveryTracking\Adapter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ChronopostAdapterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CoSpirit\DeliveryTracking\Adapter\ChronopostAdapter');
    }
}
