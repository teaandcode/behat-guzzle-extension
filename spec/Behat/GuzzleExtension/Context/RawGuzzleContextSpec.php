<?php

namespace spec\Behat\GuzzleExtension\Context;

use Guzzle\Service\Client;
use PhpSpec\ObjectBehavior;

class RawGuzzleContextSpec extends ObjectBehavior
{
    public function it_is_a_raw_guzzle_context()
    {
        $this->shouldHaveType('Behat\GuzzleExtension\Context\RawGuzzleContext');
    }

    public function it_does_not_get_a_guzzle_client()
    {
        $this->shouldThrow('\RuntimeException')->duringGetGuzzleClient();
    }

    public function it_gets_and_sets_guzzle_client(Client $client)
    {
        $this->setGuzzleClient($client);
        $this->getGuzzleClient()->shouldReturn($client);
    }

    public function it_adds_a_guzzle_header()
    {
        $client = new Client(array(
            'baseUrl' => 'foo',
            'config'  => array()
        ));

        $this->setGuzzleClient($client);
        $this->addGuzzleHeader('header', 'value');
    }

    public function it_removes_a_guzzle_header()
    {
        $client = new Client(array(
            'baseUrl' => 'foo',
            'config'  => array()
        ));

        $this->setGuzzleClient($client);
        $this->removeGuzzleHeader('header');
    }

    public function it_does_not_get_a_guzzle_parameter_that_is_not_there()
    {
        $this->getGuzzleParameter('null')->shouldReturn(null);
    } 

    public function it_gets_and_sets_a_guzzle_parameter()
    {
        $this->setGuzzleParameter('name', 'value');
        $this->getGuzzleParameter('name')->shouldReturn('value');
    }

    public function it_gets_and_sets_guzzle_parameters()
    {
        $parameters = array('foo' => 'bar');

        $this->setGuzzleParameters($parameters);
        $this->getGuzzleParameters()->shouldReturn($parameters);
    } 
}
