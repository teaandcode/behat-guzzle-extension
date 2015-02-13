<?php

namespace Behat\GuzzleExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Guzzle\Service\Client;

class GuzzleAwareInitializer implements ContextInitializer
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $parameters;

    /**
     * Initializes initializer
     *
     * @param Client $client
     * @param array  $parameters
     */
    public function __construct(Client $client, array $parameters)
    {
        $this->client     = $client;
        $this->parameters = $parameters;
    }

    /**
     * Initializes provided context
     *
     * @param Context $context
     */
    public function initializeContext(Context $context)
    {
        if (!$context instanceof GuzzleAwareContext) {
            return;
        }

        $context->setGuzzleClient($this->client);
        $context->setGuzzleParameters($this->parameters);
    }
}