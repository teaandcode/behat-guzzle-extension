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

use Behat\Gherkin\Node\PyStringNode;
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
    protected $storedResult;

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
     * Calls specified command with text
     *
     * @Given /^I called "(\S+)" with the following body text:$/
     * @When /^I call "(\S+)" with the following body text:$/
     */
    public function iCallCommandWithBodyText($command, PyStringNode $string)
    {
        $this->executeCommand(
            $command,
            array(
                'body' => $this->addStoredValues($string->getRaw())
            )
        );
    }

    /**
     * Calls specified command with fields
     *
     * @Given /^I called "(\S+)" with the following value(s?):$/
     * @When /^I call "(\S+)" with the following value(s?):$/
     */
    public function iCallCommandWithValue($command, TableNode $table)
    {
        $data = array();

        foreach ($table->getRowsHash() as $field => $value) {
            $value = $this->addStoredValues($value);
            $value = $this->castValue($value);

            $data[$field] = $value;
        }

        $this->executeCommand($command, $data);
    }

    /**
     * Calls specified command with fields
     *
     * @Given /^I called "(\S+)" with the following value(s?) from JSON:$/
     * @When /^I call "(\S+)" with the following value(s?) from JSON:$/
     */
    public function iCallCommandWithValueFromJSON(
                     $command,
        PyStringNode $string
    ) {
        $this->executeCommand(
            $command,
            json_decode($this->addStoredValues($string->getRaw()), true)
        );
    }

    /**
     * @Then I get a response with a status code of :code
     */
    public function iGetAResponseWithAStatusCodeOf($code)
    {
        $actual = $this->getGuzzleResponse()->getStatusCode();

        if ($actual != $code) {
            throw new ClientErrorResponseException(
                'Actual status code ' . $actual . ' does not match expected ' .
                'status code ' . $code
            );
        }
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

        $this->iGetAResponseWithAStatusCodeOf($code);
    }

    /**
     * @Then the response contains the following value(s):
     */
    public function theResponseContainsTheFollowingValue(TableNode $table)
    {
        $data = array();
        $item = $this->getGuzzleResult();

        foreach ($table->getRowsHash() as $field => $value) {
            $ref = &$data;
            foreach (explode('[', $field) as $part) {
                $part = trim($part, ']');
                $ref = &$ref[$part];
            }
            $ref = $this->addStoredValues($value);
        }

        $this->compareValues($item, $data);
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
            throw new ClientErrorResponseException(
                'Actual count ' . $length . ' does not match expected ' .
                'count ' . $count
            );
        }

        $data = $table->getHash();

        for ($i = 0; $i < $length; $i++) {
            $this->compareValues($list[$i], $data[$i]);
        }
    }

    /**
     * @Then /^the response is stored as "(\S+)"$/
     */
    public function theResponseIsStored($name)
    {
        $this->storedResult[$name] = $this->getGuzzleResult();
    }

    /**
     * Cast value into type depending on content
     *
     * @param string $value String value
     *
     * @access protected
     * @return mixed
     */
    protected function castValue($value)
    {
        switch ($value) {
            case 'false':
                return false;

            case 'true':
                return true;
        }

        if (is_numeric($value)) {
            $value = intval($value);
        }

        return $value;
    }

    /**
     * Adds stored values to string
     *
     * @param string $string String containing stored field markers
     *
     * @access protected
     * @return string
     */
    protected function addStoredValues($string)
    {
        preg_match_all('/\{stored\[(.*?)\]\}/si', $string, $matches);

        $length = count($matches[0]);

        for ($i = 0; $i < $length; $i++) {
            $parts = explode('][', $matches[1][$i]);
            $value = $this->storedResult;
            foreach ($parts as $part) {
                if (isset($value[$part])) {
                    $value = $value[$part];
                }
            }

            $string = str_replace($matches[0][$i], $value, $string);
        }

        return $string;
    }
}
