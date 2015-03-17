<?php

namespace spec\Behat\GuzzleExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\GuzzleExtension\Context\GuzzleAwareContext;
use Guzzle\Service\Client;
use PhpSpec\ObjectBehavior;

class GuzzleAwareInitializerSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith(
            $client,
            array(
                'base_url'             => 'https://api.travis-ci.org',
                'service_descriptions' => 'config/service.json'
            )
        );
    }

    function it_is_a_context_initializer()
    {
        $this->shouldHaveType(
            'Behat\Behat\Context\Initializer\ContextInitializer'
        );
    }

    function it_does_nothing_for_basic_contexts(Context $context)
    {
        $this->initializeContext($context);
    }

    function it_injects_client_and_parameters_in_guzzle_aware_contexts(
        GuzzleAwareContext $context,
        Client $client
    )
    {
        $context->setGuzzleClient($client)->shouldBeCalled();
        $context->setGuzzleParameters(array(
            'base_url'             => 'https://api.travis-ci.org',
            'service_descriptions' => 'config/service.json'
        ))->shouldBeCalled();

        $this->initializeContext($context);
    }
}
