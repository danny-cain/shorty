<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\Project;

class ListProjectsView extends HTMLView
{
    /**
     * @var Project[]
     */
    protected $_projects = array();
    protected $_viewProjectURITemplate = '';
    protected $_editProjectURITemplate = '';
    protected $_deleteProjectURITemplate = '';
    protected $_createProjectURI = '';
    /**
     * @var ViewInterface
     */
    protected $_searchView;

    public function display()
    {
        $this->_displayBreadcrumbs();
        echo '<h1>Select a project to edit/view</h1>';

        foreach ($this->_projects as $project)
            $this->_displayProject($project);

        echo '<div>';
            echo '<a href="'.$this->_createProjectURI.'">[create project]</a>';
        echo '</div>';

        if ($this->_searchView != null)
            $this->_searchView->display();
    }

    /**
     * @param \CannyDain\Lib\UI\Views\ViewInterface $searchView
     */
    public function setSearchView($searchView)
    {
        $this->_searchView = $searchView;
    }

    /**
     * @return \CannyDain\Lib\UI\Views\ViewInterface
     */
    public function getSearchView()
    {
        return $this->_searchView;
    }

    protected function _displayProject(Project $project)
    {
        $viewURI = strtr($this->_viewProjectURITemplate, array('#id#' => $project->getId()));

        echo '<div>';
            echo '<a href="'.$viewURI.'">'.$project->getName().'</a>';
            echo '&nbsp;&nbsp;&nbsp;';
            $this->_displayActions($project);
        echo '</div>';
    }

    protected function _displayActions(Project $project)
    {
        $editURI = strtr($this->_editProjectURITemplate, array('#id#' => $project->getId()));
        $deleteURI = strtr($this->_deleteProjectURITemplate, array('#id#' => $project->getId()));

        echo <<<HTML
<a class="actionButton" href="{$editURI}">Edit</a>
|
<form class="actionForm" method="post" action="{$deleteURI}" onsubmit="return confirm('are you sure you wish to delete this project?">
    <input type="submit" class="actionButton" value="Delete" />
</form>
HTML;

    }

    public function setViewProjectURITemplate($viewProjectURITemplate)
    {
        $this->_viewProjectURITemplate = $viewProjectURITemplate;
    }

    public function getViewProjectURITemplate()
    {
        return $this->_viewProjectURITemplate;
    }

    public function setCreateProjectURI($createProjectURI)
    {
        $this->_createProjectURI = $createProjectURI;
    }

    public function getCreateProjectURI()
    {
        return $this->_createProjectURI;
    }

    public function setDeleteProjectURITemplate($deleteProjectURITemplate)
    {
        $this->_deleteProjectURITemplate = $deleteProjectURITemplate;
    }

    public function getDeleteProjectURITemplate()
    {
        return $this->_deleteProjectURITemplate;
    }

    public function setEditProjectURITemplate($editProjectURITemplate)
    {
        $this->_editProjectURITemplate = $editProjectURITemplate;
    }

    public function getEditProjectURITemplate()
    {
        return $this->_editProjectURITemplate;
    }

    public function setProjects($projects)
    {
        $this->_projects = $projects;
    }

    public function getProjects()
    {
        return $this->_projects;
    }
}