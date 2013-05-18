<?php

namespace MeadSteve\Behationary\Tests;

use Behat\Behat\Context\BehatContext;
use MeadSteve\Behationary\IndexedContext;

class IndexedContextTest
    extends \PHPUnit_Framework_TestCase
{

    protected $behatContext;

    /**
     * @var IndexedContext
     */
    protected $testIndexedContext;

    function setUp()
    {
        $this->behatContext = new FakeContext();

        $this->testIndexedContext = new IndexedContext(
            $this->behatContext
        );
    }

    function testGetFileMethods_ReturnsAll()
    {
        $actualMethods = $this->testIndexedContext->getFileMethods();
        // The fake context should have 9 methods (7 from its parent)
        $this->assertCount(9, $actualMethods);
    }

    function testGetMethodComments_ReturnsStrings()
    {
        $actualComments = $this->testIndexedContext->getMethodComments();
        $this->assertContainsOnly("string", $actualComments, true);
    }

    function testGetFileRawSentences_ReturnsCorrectNumber()
    {
        $actualSentences = $this->testIndexedContext->getFileRawSentences();
        // There should be 1 @Then in the file wiich should be returned.
        $this->assertCount(2, $actualSentences);
    }

    function testGetFileRawSentences_ReturnsArrayofArrays()
    {
        $actualSentences = $this->testIndexedContext->getFileRawSentences();
        // There should be 1 @Then in the file wiich should be returned.
        $this->assertContainsOnly("array", $actualSentences, true);
    }


}