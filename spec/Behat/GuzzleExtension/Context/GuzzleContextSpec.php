<?php

namespace spec\Behat\GuzzleExtension\Context;

use Guzzle\Service\Client;
use PhpSpec\ObjectBehavior;

class GuzzleContextSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(array('user' => 'aaabbb'));
    }

    public function it_is_a_guzzle_context()
    {
        $this->shouldHaveType('Behat\GuzzleExtension\Context\GuzzleContext');
    }

    public function it_has_i_authenticate_as_but_will_fail()
    {
        $exceptionClass = 'Guzzle\Http\Exception\ClientErrorResponseException';

        $this->shouldThrow($exceptionClass)->duringIAuthenticateAs('foo');
    }

    public function it_has_i_authenticate_as()
    {
        $this->setGuzzleClient(
            new Client(
                array(
                    'baseUrl' => 'foo',
                    'config'  => array()
                )
            )
        );
        $this->iAuthenticateAs('user');
    }

    /*
    public function it_compares_values()
    {
        $a = array(
            'fu' => array(
                'foo' => 'bar'
            )
        );
        $b = array(
            'fu' => array(
                'foo' => 'boo'
            )
        );

        $this->shouldThrow('\Exception')->duringCompareValues($a, $b);
    }*/
}
