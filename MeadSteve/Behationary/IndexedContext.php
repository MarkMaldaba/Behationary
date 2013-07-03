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

    /**
     * @param $indexedContext
     * @throws \InvalidArgumentException
     */
    function __construct($indexedContext)
    {
        if ($indexedContext instanceof BehatContext || is_string($indexedContext)) {
            $this->indexedContext = $indexedContext;
            $this->reflector = new \ReflectionClass(
                $this->indexedContext
            );
        }
        else {
            throw new \InvalidArgumentException(
                '$indexedContext should be a string or BehatContext instance'
            );
        }
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

    /**
     * @param string $forMethodName (Optional) If the method name is given the
     *                              declaring class will be returned.
     * @return string
     */
    public function getClassName($forMethodName = null) {
        if ($forMethodName === null) {
            return $this->reflector->getName();
        }
        else {
            $method = $this->reflector->getMethod($forMethodName);
            return $method->getDeclaringClass()->getName();
        }
    }

    /**
     * @param string $forMethodName
     * @return int
     */
    public function getLineNumber($forMethodName) {
        $method = $this->reflector->getMethod($forMethodName);
        return $method->getStartLine();
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