<?php

namespace spec\Behat\GuzzleExtension\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Guzzle\Http\Exception\ClientErrorResponseException;
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

    public function it_has_i_call_command_with_value_from_json_and_response_contains_value_from_json()
    {
        $client = $this->getMockedClient(
            new Response(
                200,
                array(
                    'Content-Type' => 'application/json'
                ),
                '{"foo":"bar","fu":[{"id":4},{"id":6}]}'
            )
        );

        $string = new PyStringNode(array('{"test":"foo"}'), 1);
        $table  = new PyStringNode(array('{"foo":"bar","fu":[{"id":4}]}'), 1);

        $this->setGuzzleClient($client);
        $this->iCallCommandWithValueFromJSON('Mock', $string);
        $this->theResponseContainsTheFollowingValueFromJSON($table);
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

    public function it_should_retrieve_stored_values_by_key()
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

        $this->getStoredValue('test');
    }

    public function it_compares_response_body_with_a_pystring_including_stored_values()
    {
        $client1 = $this->getMockedClient(
            new Response(
                200,
                array(
                    'Content-Type' => 'text/css'
                ),
                'Id,Name,Age,Comment' . PHP_EOL .
                '1,"Richard Saunders",33,"some comment"' . PHP_EOL .
                '2,"Dave Nash",,"another comment"' . PHP_EOL .
                '3,"Ben Eppel",,"yet another comment"'
            )
        );

        $string = new PyStringNode(
            array(
                'Id,Name,Age,Comment',
                '1,"Richard Saunders",33,"some comment"',
                '2,"Dave Nash",,"another comment"',
                '3,{stored[person][name]},{stored[person][age]},' .
                '{stored[person][comment]}',
            ),
            1
        );

        $this->setStoredValue(
            'person',
            array(
                'name' => '"Ben Eppel"',
                'age' => '',
                'comment' => '"yet another comment"',
            )
        );

        $this->setGuzzleClient($client1);
        $this->iCallCommand('Mock');
        $this->theResponseBodyMatches($string);
    }

    public function it_should_throw_exception_when_expected_value_missing_from_array_of_actual_values()
    {
        $client = $this->getMockedClient(
            new Response(
                200,
                array(
                    'Content-Type' => 'application/json'
                ),
                '{"id":1}'
            )
        );

        $string = new PyStringNode(array('{"test":"foo"}'), 1);
        $table  = new PyStringNode(array('{"id":1,"name":"Mr Person"}'), 1);

        $this->setGuzzleClient($client);
        $this->iCallCommandWithValueFromJSON('Mock', $string);

        $this->shouldThrow(
            new ClientErrorResponseException(
                'Expected value Mr Person ' .
                'is missing from array of actual ' .
                'values at position name'
            )
        )->during(
            'theResponseContainsTheFollowingValueFromJSON',
            array($table)
        );
    }

    public function it_should_throw_exception_when_expected_json_encoded_value_missing_from_array_of_actual_values()
    {
        $client = $this->getMockedClient(
            new Response(
                200,
                array(
                    'Content-Type' => 'application/json'
                ),
                '[{"id":1}]'
            )
        );

        $string = new PyStringNode(array('{"test":"foo"}'), 1);
        $table  = new PyStringNode(
            array('[{"id":1},{"id":2}]'),
            1
        );

        $this->setGuzzleClient($client);
        $this->iCallCommandWithValueFromJSON('Mock', $string);

        $this->shouldThrow(
            new ClientErrorResponseException(
                'Expected value {"id":2} is ' .
                'missing from array of actual ' .
                'values at position 1'
            )
        )->during(
            'theResponseContainsTheFollowingValueFromJSON',
            array($table)
        );
    }
}
