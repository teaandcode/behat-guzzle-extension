<?php

namespace Behat\GuzzleExtension\Context;

use Guzzle\Service\Client;

class RawGuzzleContext implements GuzzleAwareContext
{
    /**
     * @var Client
     */
    private $guzzleClient;

    /**
     * @var array
     */
    private $guzzleParameters;

    /**
     * Sets Client instance
     *
     * @param Client $client Guzzle client
     *
     * @access public
     * @return void
     */
    public function setGuzzleClient(Client $client)
    {
        $this->guzzleClient = $client;
    }

    /**
     * Returns Client instance
     *
     * @access public
     * @return Client
     */
    public function getGuzzleClient()
    {
        if ($this->guzzleClient === null) {
            throw new \RuntimeException(
                'Guzzle client instance has not been set on Guzzle context ' .
                'class. Have you enabled the Guzzle Extension?'
            );
        }

        return $this->guzzleClient;
    }

    /**
     * Sets parameters provided for Guzzle
     *
     * @param array $parameters
     *
     * @access public
     * @return void
     */
    public function setGuzzleParameters(array $parameters)
    {
        $this->guzzleParameters = $parameters;
    }

    /**
     * Returns the parameters provided for Guzzle
     *
     * @access public
     * @return array
     */
    public function getGuzzleParameters()
    {
        return $this->guzzleParameters;
    }

    /**
     * Applies the given parameter to the Guzzle configuration. Consider that
     * all parameters get reset for each feature context
     *
     * @param string $name  The key of the parameter
     * @param string $value The value of the parameter
     *
     * @access public
     * @return void
     */
    public function setGuzzleParameter($name, $value)
    {
        $this->guzzleParameters[$name] = $value;
    }

    /**
     * Returns specific Guzzle parameter
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getGuzzleParameter($name)
    {
        if (isset($this->guzzleParameters[$name])) {
            return $this->guzzleParameters[$name];
        }
    }
}