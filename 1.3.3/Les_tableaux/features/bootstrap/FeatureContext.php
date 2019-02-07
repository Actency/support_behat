<?php

use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
/**
 * FeatureContext class.
 */
class FeatureContext implements Context {

    /**
     * @Given The following values exists:
     */
    public function theFollowingValuesExists(TableNode $table)
    {
        $hash = $table->getHash();
        print_r($hash);
    }

    /**
     * @Then I am happy
     */
    public function iAmHappy()
    {
        return true;
    }
}
