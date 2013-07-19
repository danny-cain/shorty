<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\Models;

class UserStorySearch
{
    protected $_project = 0;
    protected $_statuses = array();
    protected $_searchTerm = '';

    public function isEmpty()
    {
        if ($this->_project != 0)
            return false;

        if (count($this->_statuses) > 0)
            return false;

        if ($this->_searchTerm != '')
            return false;

        return true;
    }

    public function setProject($project)
    {
        $this->_project = $project;
    }

    public function getProject()
    {
        return $this->_project;
    }

    public function setSearchTerm($searchTerm)
    {
        $this->_searchTerm = $searchTerm;
    }

    public function getSearchTerm()
    {
        return $this->_searchTerm;
    }

    public function setStatuses($statuses)
    {
        $this->_statuses = $statuses;
    }

    public function getStatuses()
    {
        return $this->_statuses;
    }
}