<?php

namespace Behat\GuzzleExtension\Context;

use Behat\Behat\Context\Context;
use Guzzle\Service\Client;

interface GuzzleAwareContext extends Context
{
    /**
     * Sets Client instance
     *
     * @param Client $client Guzzle client
     *
     * @access public
     */
    public function setGuzzleClient(Client $client);

    /**
     * Sets parameters provided for Guzzle
     *
     * @param array $parameters
     *
     * @access public
     */
    public function setGuzzleParameters(array $parameters);
}