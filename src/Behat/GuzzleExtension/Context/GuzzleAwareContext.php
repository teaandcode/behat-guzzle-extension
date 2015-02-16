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

use Behat\Behat\Context\Context;
use Guzzle\Service\Client;

/**
 * Guzzle aware interface for contexts
 *
 * @package Behat\GuzzleExtension\Context
 * @author  Dave Nash <dave.nash@teaandcode.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 * @version Release: @package_version@
 * @link    https://github.com/teaandcode/behat-guzzle-extension GuzzleExtension
 */
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