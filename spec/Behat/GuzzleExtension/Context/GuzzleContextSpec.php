<?php

namespace spec\Behat\GuzzleExtension\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Service\Client;
use Guzzle\Service\Description\Operation;
use Guzzle\Service\Description\ServiceDescription;
use PhpSpec\ObjectBehavior;

class GuzzleContextSpec extends ObjectBehavior
{
    protected function getMockedClient(Response $response)
    {
        $operation = new Operation(
            array(
                'httpMethod' => 'GET',
                'name' => 'Mock'
            )
        );

        $service = new ServiceDescription();
        $service->addOperation($operation);

        $plugin = new MockPlugin();
        $plugin->addResponse($response);

        $client = new Client();
        $client->setDescription($service);
        $client->addSubscriber($plugin);

        return $client;
    }

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
                    'config' => array()
                )
            )
        );
        $this->iAuthenticateAs('user');
    }

    public function it_has_i_call_command()
    {
        $client = $this->getMockedClient(
            new Response(
                200,
                array(
                    'Content-Type' => 'application/json'
                ),
                '{"foo":"bar"}'
            )
        );

        $this->setGuzzleClient($client);
        $this->iCallCommand('Mock');
        $this->shouldThrow(
            '\Guzzle\Http\Exception\ClientErrorResponseException'
        )->duringIGetAResponseWithAStatusCodeOf(404);
    }

    public function it_has_i_call_command_with_body_text()
    {
        $client = $this->getMockedClient(
            new Response(
                200,
                array(
                    'Content-Type' => 'application/json'
                ),
                '{"foo":"bar"}'
            )
        );

        $string = new PyStringNode(array('{stored[foo]}'), 1);

        $this->setGuzzleClient($client);
        $this->iCallCommandWithBodyText('Mock', $string);
        $this->iGetAResponseWithAStatusCodeOf(200);
        $this->iGetASuccessfulResponse();
        $this->shouldThrow(
            '\Guzzle\Http\Exception\ClientErrorResponseException'
        )->duringIGetAnUnsuccessfulResponseWithAStatusCodeOf(404);
    }

    public function it_has_i_call_command_with_value()
    {
        $client = $this->getMockedClient(new Response(404));

        $table = new TableNode(
            array(
                array('bar', 'false'),
                array('foo', '3'),
                array('fu', 'true')
            )
        );

        $this->setGuzzleClient($client);
        $this->iCallCommandWithValue('Mock', $table);
        $this->iGetAnUnsuccessfulResponseWithAStatusCodeOf(404);
        $this->shouldThrow(
            '\Guzzle\Http\Exception\ClientErrorResponseException'
        )->duringIGetASuccessfulResponse();
    }

    public function it_has_i_call_command_with_value_from_json()
    {
        $client = $this->getMockedClient(
            new Response(
                200,
                array(
                    'Content-Type' => 'application/json'
                ),
                '{"foo":"bar"}'
            )
        );

        $string = new PyStringNode(array('{"test":"foo"}'), 1);
        $table  = new TableNode(
            array(
                array('foo', 'bar'),
            )
        );

        $this->setGuzzleClient($client);
        $this->iCallCommandWithValueFromJSON('Mock', $string);
        $this->theResponseContainsTheFollowingValue($table);
    }

    public function it_has_i_call_command_but_response_contains_a_wrong_value()
    {
        $client = $this->getMockedClient(
            new Response(
                200,
                array(
                    'Content-Type' => 'application/json'
                ),
                '{"foo":"bar"}'
            )
        );

        $table  = new TableNode(
            array(
                array('foo', 'fu'),
            )
        );

        $this->setGuzzleClient($client);
        $this->iCallCommand('Mock');
        $this->shouldThrow(
            '\Guzzle\Http\Exception\ClientErrorResponseException'
        )->duringTheResponseContainsTheFollowingValue($table);
    }

    public function it_has_i_call_command_and_response_contains_multiple_resources()
    {
        $client1 = $this->getMockedClient(
            new Response(
                200,
                array(
                    'Content-Type' => 'application/json'
                ),
                '[{"foo":"bar"},{"foo":"fu"}]'
            )
        );

        $output  = new TableNode(
            array(
                array('foo'),
                array('bar'),
                array('fu')
            )
        );

        $this->setGuzzleClient($client1);
        $this->iCallCommand('Mock');
        $this->theResponseContainsResourceWithTheFollowingData(2, $output);
        $this->theResponseIsStored('test');

        $client2 = $this->getMockedClient(
            new Response(
                200,
                array(
                    'Content-Type' => 'application/json'
                ),
                ''
            )
        );

        $input = new TableNode(
            array(
                array('test', '{stored[test][0][foo]}')
            )
        );

        $this->setGuzzleClient($client2);
        $this->iCallCommandWithValue('Mock', $input);
    }

    public function it_has_i_call_command_but_response_contains_wrong_resource_count()
    {
        $client = $this->getMockedClient(
            new Response(
                200,
                array(
                    'Content-Type' => 'application/json'
                ),
                '[{"foo":"bar"},{"foo":"fu"}]'
            )
        );

        $table  = new TableNode(
            array(
                array('foo'),
                array('bar'),
                array('fu')
            )
        );

        $this->setGuzzleClient($client);
        $this->iCallCommand('Mock');
        $this->shouldThrow(
            '\Guzzle\Http\Exception\ClientErrorResponseException'
        )->duringTheResponseContainsResourceWithTheFollowingData(1, $table);
    }
}
