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

namespace Behat\GuzzleExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Guzzle\Service\Client;

/**
 * Guzzle aware contexts initializer
 *
 * @package Behat\GuzzleExtension\Context\Initializer
 * @author  Dave Nash <dave.nash@teaandcode.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 * @version Release: @package_version@
 * @link    https://github.com/teaandcode/behat-guzzle-extension GuzzleExtension
 */
class GuzzleAwareInitializer implements ContextInitializer
{
    /**
     * @var Client
     *
     * @access private
     */
    private $client;

    /**
     * @var array
     *
     * @access private
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
     *
     * @access public
     * @return void
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