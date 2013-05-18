<?php
namespace MeadSteve\Behationary\Tests;

use Behat\Behat\Context\BehatContext;

class FakeContext
    extends BehatContext
{

    /**
     * @Then /^I should this method with arg "([^"]*)"$/
     */
    public function iShouldSeeThisMethodWithArg($Arg)
    {
    }

    /**
     * @When /^I was a method with arg "([^"]*)"$/
     */
    public function whenIWasAMethodWithArg($Arg)
    {
    }
}