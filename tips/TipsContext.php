<?php

use Drupal\Core\Url;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Drupal\node\Entity\Node;

/**
 * Class FeatureContext
 */
class Tips extends RawDrupalContext {
  /**
   * @var $output
   *   Command line output.
   */
  protected $output;

  /**
   * @Then /^I want to see the URL$/
   *
   * @throws \Exception
   */
  public function iWantToSeeTheURL() {
    try {
      $url = $this->getSession()->getCurrentUrl();
      var_dump($url);
    } catch (Exception $e) {
      throw new Exception($e);
    }
  }

  /**
   * @Then /^I want to see the page content$/
   *
   * @throws \Exception
   */
  public function iWantToSeeThePageContent() {
    try {
      $html = $this->getSession()->getPage()->getHtml();
      print($html);
    } catch (Exception $e) {
      throw new Exception($e);
    }
  }

  /**
   * @Given /^I wait (\d+) seconds$/
   */
  public function iWaitSeconds($seconds) {
    sleep($seconds);
  }

  /**
   * @Then a PDF is displayed
   */
  public function assertPdfDisplay()
  {
    $headers = $this->getSession()->getResponseHeaders();

    if (!isset($headers['Content-Type'][0]) || strcmp($headers['Content-Type'][0], 'application/pdf') != 0 ) {
      throw new Exception('No PDF displayed.');
    }
  }

  /**
   * @Then I click the back button of the navigator
   */
  public function iClickTheBackButtonInNavigator() {
    $this->getSession()->getDriver()->back();
  }

  /**
   * @Given I click the :arg1 element
   */
  public function iClickTheElement($selector) {
    $page = $this->getSession()->getPage();
    $element = $page->find('css', $selector);

    if (empty($element)) {
      throw new Exception("No html element found for the selector ('$selector')");
    }

    $element->click();
  }

  /**
   * @Given I select the first element in :arg1 list
   */
  public function iSelectTheFirstElement($selector) {
    $page = $this->getSession()->getPage();

    $options = $page->findAll('css', "#$selector option");

    /** @var \Behat\Mink\Element\NodeElement $option */
    foreach ($options as $option) {
      if (strcmp($option->getValue(), "_none") != 0) {
        $page->selectFieldOption($selector, $option->getValue());
        return;
      }
    }

    throw new Exception("Unable to find a non empty value.");
  }

  /**
   * Click some text
   *
   * @When /^I click on the text "([^"]*)"$/
   */
  public function iClickOnTheText($text)
  {
    $session = $this->getSession();
    $element = $session->getPage()->find(
      'xpath',
      $session->getSelectorsHandler()->selectorToXpath('xpath', '*//*[text()="'. $text .'"]')
    );
    if (null === $element) {
      throw new \InvalidArgumentException(sprintf('Cannot find text: "%s"', $text));
    }

    $element->click();
  }

  /**
   * @Then /^the selectbox "([^"]*)" should have a list containing:$/
   */
  public function shouldHaveAListContaining($element, \Behat\Gherkin\Node\PyStringNode $list)
  {
    $page = $this->getSession()->getPage();
    $validStrings = $list->getStrings();

    $elements = $page->findAll('css', "#$element option");

    $option_none = 0;

    /** @var \Behat\Mink\Element\NodeElement $element */
    foreach ($elements as $element) {
      $value = $element->getValue();
      if (strcmp($value, '_none') == 0) {
        $option_none = 1;
        continue;
      }

      if (!in_array($element->getValue(), $validStrings)) {
        throw new Exception ("Element $value not found.");
      }
    }

    if ((sizeof($elements) - $option_none) < sizeof($validStrings)) {
      throw new Exception ("Expected options are missing in the select list.");
    }
    elseif ((sizeof($elements) - $option_none) > sizeof($validStrings)) {
      throw new Exception ("There are more options than expected in the select list.");
    }
  }

  /**
   * Wait for AJAX to finish.
   *
   * @see \Drupal\FunctionalJavascriptTests\JSWebAssert::assertWaitOnAjaxRequest()
   *
   * @Given I wait max :arg1 seconds for AJAX to finish
   */
  public function iWaitForAjaxToFinish($seconds) {
    $condition = <<<JS
    (function() {
      function isAjaxing(instance) {
        return instance && instance.ajaxing === true;
      }
      var d7_not_ajaxing = true;
      if (typeof Drupal !== 'undefined' && typeof Drupal.ajax !== 'undefined' && typeof Drupal.ajax.instances === 'undefined') {
        for(var i in Drupal.ajax) { if (isAjaxing(Drupal.ajax[i])) { d7_not_ajaxing = false; } }
      }
      var d8_not_ajaxing = (typeof Drupal === 'undefined' || typeof Drupal.ajax === 'undefined' || typeof Drupal.ajax.instances === 'undefined' || !Drupal.ajax.instances.some(isAjaxing))
      return (
        // Assert no AJAX request is running (via jQuery or Drupal) and no
        // animation is running.
        (typeof jQuery === 'undefined' || (jQuery.active === 0 && jQuery(':animated').length === 0)) &&
        d7_not_ajaxing && d8_not_ajaxing
      );
    }());
JS;
    $result = $this->getSession()->wait($seconds * 1000, $condition);
    if (!$result) {
      throw new \RuntimeException('Unable to complete AJAX request.');
    }
  }


  /**
   * @Then I submit the form with id :arg1
   */
  public function iSubmitTheFormWithId($id_form)
  {
    $node = $this->getSession()->getPage()->findById($id_form);
    if ($node) {
      $this->getSession()->executeScript('jQuery("#'.$id_form.'").submit();');
    }
    else {
      throw new \RuntimeException('form with id '.$id_form.' not found');
    }
  }

  /**
   *  @Given I reset the session
   */
  public function iResetTheSession() {
    $this->getSession()->reset();
  }

  /**
   * @Given I grant permission :arg1 to role :arg2
   */
  public function iGrantPermissionToRole($permission, $role)
  {
    $courtierCode = \Drupal\user\Entity\Role::load($role);

    $courtierCode->grantPermission($permission);
    $courtierCode->save();
  }

  /**
   * @Given I revoke permission :arg1 from role :arg2
   */
  public function iRevokePermissionFromRole($permission, $role)
  {
    $courtierCode = \Drupal\user\Entity\Role::load($role);

    $courtierCode->revokePermission($permission);
    $courtierCode->save();
  }

  /**
   * @Then /^the option "([^"]*)" from select "([^"]*)" is selected$/
   */
  public function theOptionFromSelectIsSelected($optionValue, $select) {
    $selectField = $this->getSession()->getPage()->find('css', $select);
    if (NULL === $selectField) {
      throw new \Exception(sprintf('The select "%s" was not found in the page %s', $select, $this->getSession()
        ->getCurrentUrl()));
    }

    $optionField = $selectField->find('xpath', "//option[@selected='selected']");
    if (NULL === $optionField) {
      throw new \Exception(sprintf('No option is selected in the %s select in the page %s', $select, $this->getSession()
        ->getCurrentUrl()));
    }

    if ($optionField->getValue() != $optionValue) {
      throw new \Exception(sprintf('The option "%s" was not selected in the page %s, %s was selected', $optionValue, $this->getSession()
        ->getCurrentUrl(), $optionField->getValue()));
    }
  }

}
