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

    public function testMakeStepPretty_PreserveGroupNames()
    {
        $result = $this->testedPrettyfier->makeStepPretty(
            'the method takes argument "(?P<groupName>[^"])"'
        );
        $this->assertContains('the method takes argument "groupName"', $result);
    }

    public function testMakeStepPretty_HandlesEverythingTogether()
    {
        $result = $this->testedPrettyfier->makeStepPretty(
            '^the method takes argument "([^"])"$'
        );
        $this->assertContains('the method takes argument "something"', $result);
    }
}