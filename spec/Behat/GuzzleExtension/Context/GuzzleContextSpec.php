<?php

namespace spec\Behat\GuzzleExtension\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;
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

    public function it_has_i_call_command()
    {
        $client = new Client('http://httpbin.org');
        $client->setDescription(
            ServiceDescription::factory(
                __DIR__ . '/test.json'
            )
        );

        $this->setGuzzleClient($client);
        $this->iCallCommand('Get');
    }

    public function it_has_i_call_command_with_body_text()
    {
        $client = new Client('http://httpbin.org');
        $client->setDescription(
            ServiceDescription::factory(
                __DIR__ . '/test.json'
            )
        );

        $string = new PyStringNode(array('{stored[foo]}'), 1);

        $this->setGuzzleClient($client);
        $this->iCallCommandWithBodyText('Get', $string);
    }

    public function it_has_i_call_command_with_value()
    {
        $client = new Client('http://httpbin.org');
        $client->setDescription(
            ServiceDescription::factory(
                __DIR__ . '/test.json'
            )
        );

        $table = new TableNode(array(array('foo', 'bar')));

        $this->setGuzzleClient($client);
        $this->iCallCommandWithValue('Get', $table);
    }

    public function it_has_i_call_command_with_value_from_json()
    {
        $client = new Client('http://httpbin.org');
        $client->setDescription(
            ServiceDescription::factory(
                __DIR__ . '/test.json'
            )
        );

        $string = new PyStringNode(array('{"test":"foo"}'), 1);

        $this->setGuzzleClient($client);
        $this->iCallCommandWithValueFromJSON('Get', $string);
    }

    public function it_has_i_get_a_response_with_status_code_of_404_for_200()
    {
        $client = new Client('http://httpbin.org');
        $client->setDescription(
            ServiceDescription::factory(
                __DIR__ . '/test.json'
            )
        );

        $this->setGuzzleClient($client);
        $this->iCallCommand('Get');
        $this->shouldThrow(
            '\Guzzle\Http\Exception\ClientErrorResponseException'
        )->duringIGetAResponseWithAStatusCodeOf(404);
    }

    public function it_has_i_get_a_response_with_status_code_of()
    {
        $client = new Client('http://httpbin.org');
        $client->setDescription(
            ServiceDescription::factory(
                __DIR__ . '/test.json'
            )
        );

        $this->setGuzzleClient($client);
        $this->iCallCommand('Get');
        $this->iGetAResponseWithAStatusCodeOf(200);
    }

    public function it_has_i_get_a_successful_response_when_unsuccessful()
    {
        $client = new Client('http://httpbin.org');
        $client->setDescription(
            ServiceDescription::factory(
                __DIR__ . '/test.json'
            )
        );

        $this->setGuzzleClient($client);
        $this->iCallCommand('Get404');
        $this->shouldThrow(
            '\Guzzle\Http\Exception\ClientErrorResponseException'
        )->duringIGetASuccessfulResponse();
    }

    public function it_has_i_get_a_successful_response()
    {
        $client = new Client('http://httpbin.org');
        $client->setDescription(
            ServiceDescription::factory(
                __DIR__ . '/test.json'
            )
        );

        $this->setGuzzleClient($client);
        $this->iCallCommand('Get');
        $this->iGetASuccessfulResponse();
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
