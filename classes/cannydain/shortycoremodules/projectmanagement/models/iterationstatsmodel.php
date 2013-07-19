<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\Models;

class IterationStatsModel
{
    /**
     * @var Iteration
     */
    protected $_iteration;
    protected $_effort = 0;
    protected $_remainingEffort = 0;

    public function getDays()
    {
        return intval(date('z', $this->_iteration->getIterationEnd() - $this->_iteration->getIterationStart()));
    }

    public function setEffort($effort)
    {
        $this->_effort = $effort;
    }

    public function getEffort()
    {
        return $this->_effort;
    }

    public function setIteration($iteration)
    {
        $this->_iteration = $iteration;
    }

    public function getIteration()
    {
        return $this->_iteration;
    }

    public function setRemainingEffort($remainingEffort)
    {
        $this->_remainingEffort = $remainingEffort;
    }

    public function getRemainingEffort()
    {
        return $this->_remainingEffort;
    }
}