<?php
namespace MeadSteve\Behationary;

use Behat\Behat\Context\BehatContext;

class IndexedContext
{
    protected $indexedContext;
    /**
     * @var \ReflectionClass
     */
    protected $reflector;

    protected $sentenceRegex
        = '#@(Then|When|Given)[ ]*\/(?<sentence>[^@\/]*)\/#';

    function __construct(BehatContext $indexedContext)
    {
        $this->indexedContext = $indexedContext;
        $this->reflector = new \ReflectionClass(
            $this->indexedContext
        );
    }

    public function getFileMethods()
    {
        return $this->reflector->getMethods();
    }

    public function getMethodComments()
    {
        $methods = array_filter(
            $this->getFileMethods(),
            array($this, 'filterPublic')
        );
        $methodDocs = array_map(
            array($this, 'getMethodDoc'),
            $methods
        );
        $methodNames = array_map(
            array($this, 'getMethodName'),
            $methods
        );

        return array_combine($methodNames, $methodDocs);
    }

    public function getFileRawSentences()
    {
        $methodSentences = array();

        $matches = array();
        foreach($this->getMethodComments() as $name => $comment) {
            preg_match_all(
                $this->sentenceRegex,
                $comment,
                $matches
            );
            $methodSentences[$name] = array();
            foreach($matches['sentence'] as $sentence) {
                $methodSentences[$name][] = $sentence;
            }
        }

        $methodSentences = array_filter(
            $methodSentences,
            function($sentences) {
                return (count($sentences) > 0);
            }
        );

        return $methodSentences;
    }

    public function getClassName() {
        return $this->reflector->getName();
    }

    protected function filterPublic(\ReflectionMethod $method)
    {
        return $method->isPublic();
    }

    protected function getMethodDoc(\ReflectionMethod $method)
    {
        return $method->getDocComment();
    }

    protected function getMethodName(\ReflectionMethod $method)
    {
        return $method->getName();
    }
}