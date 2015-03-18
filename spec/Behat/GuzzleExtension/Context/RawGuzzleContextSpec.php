<?php

namespace spec\Behat\GuzzleExtension\Context;

use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Service\Client;
use Guzzle\Service\Description\Operation;
use Guzzle\Service\Description\ServiceDescription;
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

    public function it_adds_and_removes_a_guzzle_header()
    {
        $client = new Client(array(
            'baseUrl' => 'foo',
            'config'  => array()
        ));

        $this->setGuzzleClient($client);
        $this->addGuzzleHeader('header', 'value');
        $this->removeGuzzleHeader('header');
    }

    public function it_gets_and_sets_guzzle_parameters_and_paramter()
    {
        $parameters = array('foo' => 'bar');

        $this->setGuzzleParameters($parameters);
        $this->getGuzzleParameters()->shouldReturn($parameters);

        $this->setGuzzleParameter('foo', 'fu');
        $this->getGuzzleParameter('foo')->shouldReturn('fu');
        $this->getGuzzleParameter('bar')->shouldReturn(null);
    }
}
