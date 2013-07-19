<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\Project;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\UserStory;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\UserStorySearch;

class StorySearchView extends HTMLView implements FormHelperConsumer
{
    /**
     * @var FormHelper
     */
    protected $_formHelper;

    /**
     * @var UserStorySearch
     */
    protected $_search;

    protected $_projects = array();

    protected $_searchURI = '';

    /**
     * @var ViewInterface
     */
    protected $_searchResultsView;

    public function display()
    {
        echo '<h1>Search for User Stories</h1>';
        $this->_formHelper->startForm($this->_searchURI, FormHelper::METHOD_GET);
            $this->_formHelper->select('project', 'Project',$this->_projects, $this->_search->getProject(), 'Project to search within');
            $this->_formHelper->multiSelect('status', 'Status', UserStory::getAllStatusNamesByID(), $this->_search->getStatuses(), 'Only return projects in the specified status (selecting none is the same as selecting all)');
            $this->_formHelper->editText('query', 'Search Term', $this->_search->getSearchTerm(), 'The text to search for');

            $this->_formHelper->submitButton('Search');
        $this->_formHelper->endForm();

        if ($this->_searchResultsView != null)
            $this->_searchResultsView->display();
    }

    public function updateModelFromRequest(Request $request)
    {
        $this->_search->setProject($request->getParameter('project'));
        $this->_search->setSearchTerm($request->getParameter('query'));
        $this->_search->setStatuses($request->getParameter('status'));

        if (!is_array($this->_search->getStatuses()))
            $this->_search->setStatuses(array());
    }

    /**
     * @param \CannyDain\Lib\UI\Views\ViewInterface $searchResultsView
     */
    public function setSearchResultsView($searchResultsView)
    {
        $this->_searchResultsView = $searchResultsView;
    }

    /**
     * @return \CannyDain\Lib\UI\Views\ViewInterface
     */
    public function getSearchResultsView()
    {
        return $this->_searchResultsView;
    }

    /**
     * @param Project[] $projects
     */
    public function setProjects($projects)
    {
        $this->_projects = array(0 => '- All Projects -');

        foreach ($projects as $project)
            $this->_projects[$project->getId()] = $project->getName();
    }

    public function setSearchURI($searchURI)
    {
        $this->_searchURI = $searchURI;
    }

    public function getSearchURI()
    {
        return $this->_searchURI;
    }

    /**
     * @param \CannyDain\ShortyCoreModules\ProjectManagement\Models\UserStorySearch $search
     */
    public function setSearch($search)
    {
        $this->_search = $search;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\ProjectManagement\Models\UserStorySearch
     */
    public function getSearch()
    {
        return $this->_search;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}