<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
/**
 * FeatureContext class.
 */
class FeatureContext implements Context {

    /**
     * @Given I am here
     */
    public function iAmHere()
    {
        return true;
    }

    /**
     * @When I wait :arg1 seconds
     */
    public function waitSeconds($arg1)
    {
        sleep($arg1);
    }

    /**
     * @Then I am happy
     */
    public function iAmHappy()
    {
        return true;
    }
}
