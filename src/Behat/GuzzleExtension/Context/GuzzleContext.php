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

use Behat\Gherkin\Node\TableNode;
use Guzzle\Http\Exception\ClientErrorResponseException;

/**
 * Guzzle context for Behat BDD tool
 * Provides raw Guzzle integration and base step definitions
 *
 * @package Behat\GuzzleExtension\Context
 * @author  Dave Nash <dave.nash@teaandcode.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 * @version Release: @package_version@
 * @link    https://github.com/teaandcode/behat-guzzle-extension GuzzleExtension
 */
class GuzzleContext extends RawGuzzleContext
{
    /**
     * @var array
     *
     * @access protected
     */
    protected $users;

    public function __construct(array $users = array())
    {
        $this->users = $users;
    }

    /**
     * Calls specified command
     *
     * @Given /^I authenticated as "(\S+)"$/
     * @When /^I authenticate as "(\S+)"$/
     */
    public function iAuthenticateAs($user)
    {
        if (!isset($this->users[$user])) {
            throw new ClientErrorResponseException(
                'User ' . $user . ' does not exist'
            );
        }

        $this->addGuzzleHeader(
            'Authorization',
            'Bearer ' . $this->users[$user]
        );
    }

    /**
     * Calls specified command
     *
     * @Given /^I called "(\S+)"$/
     * @When /^I call "(\S+)"$/
     */
    public function iCallCommand($command)
    {
        $this->executeCommand($command);
    }

    /**
     * Calls specified command with fields
     *
     * @Given /^I called "(\S+)" with the following field(s?):$/
     * @When /^I call "(\S+)" with the following field(s?):$/
     */
    public function iCallCommandWithField($command, TableNode $table)
    {
        $data = array();

        foreach ($table->getRowsHash() as $field => $value) {
            if (is_numeric($value)) {
                $value = intval($value);
            }

            $data[$field] = $value;
        }

        $this->executeCommand($command, $data);
    }

    /**
     * @Then I get a successful response
     */
    public function iGetASuccessfulResponse()
    {
        if (!$this->getGuzzleResponse()->isSuccessful()) {
            throw new ClientErrorResponseException(
                'Response unsuccessful with status code ' .
                $this->getGuzzleResponse()->getStatusCode()
            );
        }
    }

    /**
     * @Then I get an unsuccessful response with a status code of :code
     */
    public function iGetAnUnsuccessfulResponseWithAStatusCodeOf($code)
    {
        if ($this->getGuzzleResponse()->isSuccessful()) {
            throw new ClientErrorResponseException('Response successful');
        }

        $actual = $this->getGuzzleResponse()->getStatusCode();

        if ($actual != $code) {
            throw new ClientErrorResponseException(
                'Actual status code ' . $actual . ' does not match expected ' .
                'status code ' . $code
            );
        }
    }

    /**
     * @Then the response contains the following value(s):
     */
    public function theResponseContainsTheFollowingValue(TableNode $table)
    {
        $item = $this->getGuzzleResult();
        $data = $table->getRowsHash();

        $this->compareArrayValues($item, $data);
    }

    /**
     * @Then the response contains :count resource(s) with the following data:
     */
    public function theResponseContainsResourceWithTheFollowingData(
        $count,
        TableNode $table
    ) {
        $list   = $this->getGuzzleResult();
        $length = count($list);

        if ($length != $count) {
            throw new Exception(
                'Actual count ' . $length . ' does not match expected ' .
                'count ' . $count
            );
        }

        $data = $table->getHash();

        for ($i = 0; $i < $length; $i++) {
            $this->compareArrayValues($list[$i], $data[$i]);
        }
    }
}
