<?php
namespace MeadSteve\Behationary\Tests;

use MeadSteve\Behationary\StepPrettyfier;

class StepPrettyfierTest
    extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \MeadSteve\Behationary\StepPrettyfier
     */
    protected $testedPrettyfier;

    public function setUp()
    {
        $this->testedPrettyfier = new StepPrettyfier();
    }

    public function testMakeStepPretty_ReturnsStringArray()
    {
        $result = $this->testedPrettyfier->makeStepPretty(
            "something"
        );
        $this->assertInternalType("array", $result);
        $this->assertContainsOnly("string", $result, true);
    }

    public function testMakeStepPretty_RemovesStartAndEndMatchers()
    {
        $result = $this->testedPrettyfier->makeStepPretty(
            '^the method does a thing$'
        );
        $this->assertContains('the method does a thing', $result);
    }

    public function testMakeStepPretty_AddsVariablePlaceHolders()
    {
        $result = $this->testedPrettyfier->makeStepPretty(
            'the method takes argument "([^"])"'
        );
        $this->assertContains('the method takes argument "something"', $result);
    }

    public function testMakeStepPretty_AddsVariablePlaceHoldersFor2Args()
    {
        $result = $this->testedPrettyfier->makeStepPretty(
            'the method takes argument "([^"])" and "([^"])"'
        );
        $this->assertContains(
            'the method takes argument "something" and "something"',
            $result
        );
    }

    public function testMakeStepPretty_AddsVariablePlaceHoldersFor2NamedArgs()
    {
        $result = $this->testedPrettyfier->makeStepPretty(
            'select the "(?P<Option>[^"]*)" option for "(?P<Choice>[^"]*)"'
        );
        $this->assertContains(
            'select the "Option" option for "Choice"',
            $result
        );
    }

    public function testMakeStepPretty_PreserveGroupNames()
    {
        $result = $this->testedPrettyfier->makeStepPretty(
            'the method takes argument "(?P<groupName>[^"])"'
        );
        $this->assertContains('the method takes argument "groupName"', $result);
    }

    public function testMakeStepPretty_SplitsForOptionalGroups()
    {
        $result = $this->testedPrettyfier->makeStepPretty(
            '(?:|the) method takes argument'
        );
        $this->assertContains('the method takes argument', $result);
        $this->assertContains('method takes argument', $result);
    }

    public function testMakeStepPretty_SplitsForSwitchGroups()
    {
        $result = $this->testedPrettyfier->makeStepPretty(
            '(?:be|benot) that is the question'
        );
        $this->assertContains('be that is the question', $result);
        $this->assertContains('benot that is the question', $result);
    }

    public function testMakeStepPretty_SplitsForTripleSwitchGroups()
    {
        $result = $this->testedPrettyfier->makeStepPretty(
            '(?:be|benot|something) that is the question'
        );
        $this->assertContains('be that is the question', $result);
        $this->assertContains('benot that is the question', $result);
        $this->assertContains('something that is the question', $result);
    }

    public function testMakeStepPretty_HandlesEverythingTogether()
    {
        $result = $this->testedPrettyfier->makeStepPretty(
            '^the method takes argument "([^"])"$'
        );
        $this->assertContains('the method takes argument "something"', $result);
    }
}