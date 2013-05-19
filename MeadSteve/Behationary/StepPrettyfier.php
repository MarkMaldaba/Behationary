<?php
namespace MeadSteve\Behationary;

class StepPrettyfier
{
    public function makeStepPretty($step)
    {
        $steps = $this->removeAnchors(array($step));
        $steps = $this->addNamedVariablePlaceHolders($steps);
        $steps = $this->addVariablePlaceHolders($steps);
        return $steps;
    }

    protected function removeAnchors($steps)
    {
        foreach($steps as $key => $step) {
            if (mb_substr ($steps[$key], 0, 1) == "^") {
                $steps[$key] = mb_substr($steps[$key], 1);
            }
            if (mb_substr($steps[$key], -1, 1) == "$") {
                $steps[$key] = mb_substr($steps[$key],
                                         0,
                                         mb_strlen($steps[$key]) - 1);
            }
        }
        return $steps;
    }

    protected function addNamedVariablePlaceHolders($steps)
    {
        foreach($steps as $key => $step) {
            $steps[$key] = preg_replace_callback(
                '#"\(\?P<(.+?)>.+\)"#',
                function($match) {
                    return '"' . $match[1] . '"';
                },
                $step
            );
        }
        return $steps;
    }
    protected function addVariablePlaceHolders($steps)
    {
        foreach($steps as $key => $step) {
            $steps[$key] = preg_replace('#"\(.+?\)"#', '"something"', $step);
        }
        return $steps;
    }
}