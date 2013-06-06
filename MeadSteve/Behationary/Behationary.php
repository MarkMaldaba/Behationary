<?php
namespace MeadSteve\Behationary;

use Behat\Behat\Context\BehatContext;

class Behationary {

    /**
     * @var StepPrettyfier
     */
    protected $stepPrettyfier;

    /**
     * @var IndexedContext[]
     */
    protected $contexts = array();

    public function __construct(StepPrettyfier $stepPrettyfier = null)
    {
        if ($stepPrettyfier === null) {
            $stepPrettyfier = new StepPrettyfier();
        }
        $this->stepPrettyfier = $stepPrettyfier;
    }

    public function addContext(BehatContext $context) {
        $this->addIndexedContext(new IndexedContext($context));
        return $this;
    }

    public function addContexts(array $contexts) {
        array_walk($contexts, array($this, 'addContext'));
        return $this;
    }

    public function addIndexedContext(IndexedContext $indexedContext) {
        $this->contexts[] = $indexedContext;
        return $this;
    }

    public function getAllSteps() {
        $arr = array();
        foreach($this->contexts as $indexedContext) {
            $byMethod = $indexedContext->getFileRawSentences();
            $arr = $this->addAllMethods($arr, $byMethod, $indexedContext);
        }
        return $arr;
    }

    protected function addSentencesToArr($arr, $fullVariableName, $sentences)
    {
        foreach ($sentences as $baseSentence) {
            $mappedSentences = $this->stepPrettyfier->makeStepPretty(
                $baseSentence
            );
            foreach ($mappedSentences as $singleSentance) {
                $arr[$singleSentance] = $fullVariableName;
            }
        }
        return $arr;
    }

    /**
     * @param array $arr
     * @param array $byMethod
     * @param IndexedContext $indexedContext
     * @return mixed
     */
    protected function addAllMethods($arr, $byMethod, $indexedContext)
    {
        foreach ($byMethod as $methodName => $sentences) {
            $fullVariableName = $indexedContext->getClassName($methodName)
                . "::"
                . $methodName;
            $arr = $this->addSentencesToArr(
                $arr,
                $fullVariableName,
                $sentences
            );
        }
        return $arr;
    }
}