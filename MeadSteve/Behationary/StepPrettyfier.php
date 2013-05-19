<?php
namespace MeadSteve\Behationary;

class StepPrettyfier
{
    public function makeStepPretty($step)
    {
        $step = $this->removeAnchors($step);
        $steps = $this->splitForOptional(array($step));
        $steps = $this->addNamedVariablePlaceHolders($steps);
        $steps = $this->addVariablePlaceHolders($steps);
        return array_map(array($this, 'tidyStep'), $steps);
    }

    protected function removeAnchors($step)
    {
        if (mb_substr ($step, 0, 1) == "^") {
            $step = mb_substr($step, 1);
        }
        if (mb_substr($step, -1, 1) == "$") {
            $step = mb_substr($step, 0, mb_strlen($step) - 1);
        }
        return $step;
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

    protected function splitForOptional($steps)
    {
        $repeatLoop = true;
        while($repeatLoop) {
            $repeatLoop = false;
            foreach($steps as $key => $step) {
                $matches = array();
                preg_match(
                    '#\(\?:(?P<options>(.*?)(\|(.*?))*)\)#s',
                    $step,
                    $matches
                );
                if (isset($matches['options'])) {
                    unset($steps[$key]);
                    $options = explode("|", $matches['options']);
                    foreach($options as $option) {
                        $steps[] = preg_replace(
                            '#\(\?:(.*?)(?:\|(.*?))*\)#s',
                            $option,
                            $step,
                            1
                        );
                    }
                    $repeatLoop = true;
                }
            }
        }
        return $steps;
    }

    protected function tidyStep($step)
    {
        return rtrim(ltrim($step));
    }
}