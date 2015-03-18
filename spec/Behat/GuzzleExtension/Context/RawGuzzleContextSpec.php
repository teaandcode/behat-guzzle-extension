<?php

namespace spec\Behat\GuzzleExtension\Context;

use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;
use PhpSpec\ObjectBehavior;

class RawGuzzleContextSpec extends ObjectBehavior
{
    public function it_is_a_raw_guzzle_context()
    {
        $this->shouldHaveType('Behat\GuzzleExtension\Context\RawGuzzleContext');
    }

    public function it_executes_a_command_that_returns_a_404()
    {
        $client = new Client('http://httpbin.org');
        $client->setDescription(
            ServiceDescription::factory(
                __DIR__ . '/test.json'
            )
        );

        $this->setGuzzleClient($client);
        $this->executeCommand('Get404');
        $this->getGuzzleResponse()
            ->shouldReturnAnInstanceOf('Guzzle\Http\Message\Response');
    }

    public function it_executes_a_command_that_returns_nothing()
    {
        $client = new Client('http://httpbin.org');
        $client->setDescription(
            ServiceDescription::factory(
                __DIR__ . '/test.json'
            )
        );

        $this->setGuzzleClient($client);
        $this->executeCommand('GetRobots');
        $this->getGuzzleResponse()
            ->shouldReturnAnInstanceOf('Guzzle\Http\Message\Response');
    }

    public function it_executes_a_command()
    {
        $client = new Client('http://httpbin.org');
        $client->setDescription(
            ServiceDescription::factory(
                __DIR__ . '/test.json'
            )
        );

        $this->setGuzzleClient($client);
        $this->executeCommand('Get');
        $this->getGuzzleResponse()
            ->shouldReturnAnInstanceOf('Guzzle\Http\Message\Response');
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

    public function it_gets_guzzle_response()
    {
        $this->getGuzzleResponse()->shouldReturn(null);
    }

    public function it_gets_guzzle_result()
    {
        $this->getGuzzleResult()->shouldReturn(null);
    }
}
