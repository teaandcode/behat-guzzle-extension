<?php
/**
 * Behat Guzzle Extension
 *
 * PHP version 5
 *
 * @package Behat\GuzzleExtension
 * @author  Dave Nash <dave.nash@teaandcode.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 * @version GIT: $Id$
 * @link    https://github.com/teaandcode/behat-guzzle-extension GuzzleExtension
 */

namespace Behat\GuzzleExtension\Context;

use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Message\Response;
use Guzzle\Service\Client;

/**
 * Raw Guzzle context for Behat BDD tool
 * Provides raw Guzzle integration (without step definitions) and web assertions
 *
 * @package Behat\GuzzleExtension\Context
 * @author  Dave Nash <dave.nash@teaandcode.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 * @version Release: @package_version@
 * @link    https://github.com/teaandcode/behat-guzzle-extension GuzzleExtension
 */
class RawGuzzleContext implements GuzzleAwareContext
{
    /**
     * @var Client
     *
     * @access private
     */
    private $guzzleClient;

    /**
     * @var array
     *
     * @access private
     */
    private $guzzleParameters;

    /**
     * @var Response
     *
     * @access private
     */
    private $guzzleResponse;

    /**
     * @var array
     *
     * @access private
     */
    private $guzzleResult;

    /**
     * Execute command
     *
     * @param string $command Command to execute
     * @param array  $data    Data to send
     *
     * @access protected
     * @return void
     */
    public function executeCommand($command, array $data = array())
    {
        $command = $this->getGuzzleClient()->getCommand($command, $data);

        try {
            $result = $this->getGuzzleClient()->execute($command);
        } catch (ClientErrorResponseException $e) {
            $this->guzzleResponse = $e->getResponse();

            return;
        }

        if (!is_array($result)) {
            $result = array($result);
        }

        $this->guzzleResponse = $command->getResponse();
        $this->guzzleResult   = $result;
    }

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
                'class.' . chr(10) . 'Have you enabled the Guzzle Extension?'
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
     * @access public
     * @return mixed
     */
    public function getGuzzleParameter($name)
    {
        if (isset($this->guzzleParameters[$name])) {
            return $this->guzzleParameters[$name];
        }
    }

    /**
     * Returns result array
     *
     * @access public
     * @return array
     */
    public function getGuzzleResult()
    {
        return $this->guzzleResult;
    }

    /**
     * Returns Response instance
     *
     * @access public
     * @return Response
     */
    public function getGuzzleResponse()
    {
        return $this->guzzleResponse;
    }
}
