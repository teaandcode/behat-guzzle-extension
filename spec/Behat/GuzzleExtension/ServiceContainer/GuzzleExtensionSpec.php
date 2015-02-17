<?php

namespace spec\Behat\GuzzleExtension\ServiceContainer;

use PhpSpec\ObjectBehavior;

class GuzzleExtensionSpec extends ObjectBehavior
{
    function it_is_a_testwork_extension()
    {
        $this->shouldHaveType('Behat\Testwork\ServiceContainer\Extension');
    }

    function it_is_named_guzzle()
    {
        $this->getConfigKey()->shouldReturn('guzzle');
    }
}
