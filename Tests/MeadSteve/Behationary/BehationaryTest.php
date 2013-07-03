<?php

namespace MeadSteve\Behationary\Tests;

use MeadSteve\Behationary\Behationary;
use MeadSteve\Behationary\IndexedContext;
use MeadSteve\Behationary\StepPrettyfier;

class BehationaryTest
    extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Behationary
     */
    protected $testedBehationary;
    protected $mockPrettyfier;

    public function setUp()
    {
        $this->setupTestObject();
    }

    protected function setupTestObject($doPrettyStatements = false)
    {
        new StepPrettyfier();
        $this->mockPrettyfier = $this->getMock(
            'MeadSteve\Behationary\StepPrettyfier',
            array('makeStepPretty')
        );
        if ($doPrettyStatements) {
            $this->mockPrettyfier
                ->expects($this->any())
                ->method("makeStepPretty")
                ->will($this->returnValue(array("PrettyPlaceHolder")));
        }
        else {
            $this->mockPrettyfier
                ->expects($this->any())
                ->method("makeStepPretty")
                ->will($this->returnCallback(function($calledWith) {
                    return array($calledWith);
                }));
        }

        $this->testedBehationary = new Behationary(
            $this->mockPrettyfier
        );
    }

    public function testGetAllSteps_ReturnsEmptyArrayWhenEmpty() {
        $arr = $this->testedBehationary->getAllSteps();
        $this->assertCount(0, $arr);
    }

    public function testGetAllSteps_ReturnsStringArrayOfCorrectLength() {
        $mockData = array(
            'MethodOne' => array('^I call method One$')
        );
        $this->loadMockData($mockData);
        $arr = $this->testedBehationary->getAllSteps();
        $this->assertContainsOnly("array", $arr, true);
        $this->assertCount(1, $arr);
    }

    public function testGetAllSteps_ContainsSentenceIndexes() {
        $mockData = array(
            'MethodOne' => array('^I call method One$')
        );
        $this->loadMockData($mockData);
        $arr = $this->testedBehationary->getAllSteps();

        $this->assertArrayHasKey('^I call method One$', $arr);
    }

    public function testGetAllSteps_ContainsPrettyfiedIndexes() {
        $this->setupTestObject(true);
        $mockData = array(
            'MethodOne' => array('^I call method One$')
        );
        $this->loadMockData($mockData);
        $arr = $this->testedBehationary->getAllSteps();

        $this->assertArrayHasKey('PrettyPlaceHolder', $arr);
    }

    public function testGetAllSteps_ContainsFullMethodName() {
        $mockData = array(
            'MethodOne' => array('^I call method One$')
        );
        $this->loadMockData($mockData);
        $arr = $this->testedBehationary->getAllSteps();

        $item = array_pop($arr);

        $this->assertEquals('ClassName::MethodOne', $item['fullVariableName']);
    }

    public function testGetAllSteps_ContainsLineNumber() {
        $mockData = array(
            'MethodOne' => array('^I call method One$')
        );
        $this->loadMockData($mockData);
        $arr = $this->testedBehationary->getAllSteps();

        $item = array_pop($arr);

        $this->assertEquals(1, $item['lineNumber']);
    }

    protected function loadMockData($mockData,
                                    $className = "ClassName",
                                    $lineNumber = 1)
    {
        $mockContext = $this->getMock(
            'MeadSteve\Behationary\IndexedContext',
            array('getFileRawSentences', 'getClassName', 'getLineNumber'),
            array(),
            'MockContext',
            false
        );
        $mockContext->expects($this->any())
            ->method('getFileRawSentences')
            ->will($this->returnValue($mockData));
        $mockContext->expects($this->any())
            ->method('getClassName')
            ->will($this->returnValue($className));
        $mockContext->expects($this->any())
            ->method('getLineNumber')
            ->will($this->returnValue($lineNumber));

        $this->testedBehationary->addIndexedContext($mockContext);
    }

}