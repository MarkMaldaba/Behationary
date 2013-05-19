<?php
namespace MeadSteve\Behationary;

class StepPrettyfier
{
    public function makeStepPretty($step)
    {
        $step = $this->removeAnchors($step);
        $step = $this->addVariablePlaceHolders($step);
        return $step;
    }

    protected function removeAnchors($step)
    {
        if (mb_substr ($step, 0, 1) == "^") {
            $step = mb_substr ($step, 1);
        }
        if (mb_substr($step, -1, 1) == "$") {
            $step = mb_substr($step, 0, mb_strlen($step) - 1);
        }
        return $step;
    }

    protected function addVariablePlaceHolders($step)
    {
        $step = preg_replace('#"\(.+\)"#', '"something"', $step);
        return $step;
    }
}