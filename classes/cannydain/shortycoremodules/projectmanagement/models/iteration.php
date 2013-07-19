<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\Models;

class Iteration
{
    protected $_id = 0;
    protected $_project = 0;
    protected $_iterationStart = 0;
    protected $_iterationEnd = 0;

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setIterationEnd($iterationEnd)
    {
        $this->_iterationEnd = $iterationEnd;
    }

    public function getIterationEnd()
    {
        return $this->_iterationEnd;
    }

    public function setIterationStart($iterationStart)
    {
        $this->_iterationStart = $iterationStart;
    }

    public function getIterationStart()
    {
        return $this->_iterationStart;
    }

    public function setProject($project)
    {
        $this->_project = $project;
    }

    public function getProject()
    {
        return $this->_project;
    }
}