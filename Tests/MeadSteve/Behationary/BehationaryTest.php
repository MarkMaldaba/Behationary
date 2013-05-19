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
        new StepPrettyfier();
        $this->mockPrettyfier = $this->getMock(
            'MeadSteve\Behationary\StepPrettyfier',
            null
        );
        $this->mockPrettyfier
             ->expects($this->any())
             ->method("makeStepPretty")
             ->will($this->returnArgument(0));
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
        $this->assertContainsOnly("string", $arr, true);
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

    public function testGetAllSteps_ContainsFullMethodName() {
        $mockData = array(
            'MethodOne' => array('^I call method One$')
        );
        $this->loadMockData($mockData);
        $arr = $this->testedBehationary->getAllSteps();

        $this->assertContains('ClassName::MethodOne', $arr);
    }

    protected function loadMockData($mockData,
                                    $className = "ClassName")
    {
        $mockContext = $this->getMock(
            'MeadSteve\Behationary\IndexedContext',
            array('getFileRawSentences', 'getClassName'),
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

        $this->testedBehationary->addIndexedContext($mockContext);
    }

}