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

    public function testMakeStepPretty_ReturnsString()
    {
        $result = $this->testedPrettyfier->makeStepPretty(
            "something"
        );
        $this->assertInternalType("string", $result);
    }

    public function testMakeStepPretty_RemovesStartAndEndMatchers()
    {
        $result = $this->testedPrettyfier->makeStepPretty(
            '^the method does a thing$'
        );
        $this->assertEquals('the method does a thing', $result);
    }

    public function testMakeStepPretty_AddsVariablePlaceHolders()
    {
        $result = $this->testedPrettyfier->makeStepPretty(
            'the method takes argument "([^"])"'
        );
        $this->assertEquals('the method takes argument "something"', $result);
    }

    public function testMakeStepPretty_HandlesEverythingTogether()
    {
        $result = $this->testedPrettyfier->makeStepPretty(
            '^the method takes argument "([^"])"$'
        );
        $this->assertEquals('the method takes argument "something"', $result);
    }
}