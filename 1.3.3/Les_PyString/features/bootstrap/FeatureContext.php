<?php

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
/**
 * FeatureContext class.
 */
class FeatureContext implements Context {
    /**
     * @Then I am happy
     */
    public function iAmHappy()
    {
        return true;
    }

    /**
     * @Given a blog post :arg1 with:
     */
    public function aBlogPostWith($arg1, PyStringNode $string)
    {
        printf("Titre: %s\n", $arg1);
        print($string->getRaw());
    }
}
