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
            foreach($byMethod as $methodName => $sentences) {
                foreach($sentences as $sentence) {
                    $sentence = $this->stepPrettyfier->makeStepPretty(
                        $sentence
                    );
                    $arr[$sentence] = $indexedContext->getClassName()
                                    . "::"
                                    . $methodName;
                }
            }
        }
        return $arr;
    }
}