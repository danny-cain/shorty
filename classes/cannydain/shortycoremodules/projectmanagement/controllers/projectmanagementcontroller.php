<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\Controllers;

use CannyDain\Lib\CommentsManager\CommentsManager;
use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\ViewFactory;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\CommentsConsumer;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Consumers\TimeEntryConsumer;
use CannyDain\Shorty\Consumers\ViewFactoryConsumer;
use CannyDain\Shorty\TimeTracking\TimeTracker;
use CannyDain\ShortyCoreModules\ProjectManagement\DataAccess\ProjectManagementDataAccess;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\Iteration;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\Project;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\UserStory;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\UserStorySearch;
use CannyDain\ShortyCoreModules\ProjectManagement\Views\EditIteration;
use CannyDain\ShortyCoreModules\ProjectManagement\Views\EditProjectView;
use CannyDain\ShortyCoreModules\ProjectManagement\Views\EditStoryView;
use CannyDain\ShortyCoreModules\ProjectManagement\Views\IterationStatsView;
use CannyDain\ShortyCoreModules\ProjectManagement\Views\ListProjectsView;
use CannyDain\ShortyCoreModules\ProjectManagement\Views\ListStoriesView;
use CannyDain\ShortyCoreModules\ProjectManagement\Views\StorySearchResultsView;
use CannyDain\ShortyCoreModules\ProjectManagement\Views\StorySearchView;

class ProjectManagementController implements ControllerInterface, ViewFactoryConsumer, RouterConsumer, RequestConsumer, DependencyConsumer, CommentsConsumer, TimeEntryConsumer
{
    /**
     * @var ViewFactory
     */
    protected $_viewFactory;

    /**
     * @var TimeTracker
     */
    protected $_timeTracker;

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var CommentsManager
     */
    protected $_commentsManager;

    public function Index()
    {
        $view = $this->_view_ListProjects($this->datasource()->getAllProjects());

        return $view;
    }

    public function Search()
    {
        $view = $this->_view_Search();
        $view->updateModelFromRequest($this->_request);

        if (!$view->getSearch()->isEmpty())
        {
            $resultsView = $this->_view_SearchResults();
            $resultsView->setStories($this->datasource()->searchStories($view->getSearch()));
            $resultsView->setEditURITemplate($this->_router->getURI(new Route(__CLASS__, 'EditStory', array('#id#'))));

            $view->setSearchResultsView($resultsView);
        }

        return $view;
    }

    public function IterationStats($projectID)
    {
        $view = new IterationStatsView();
        $this->_dependencies->applyDependencies($view);

        $view->setStats($this->datasource()->getIterationStats($projectID));

        return $view;
    }

    public function ViewStories($projectID)
    {
        $project = $this->datasource()->getProject($projectID);
        $stories = $this->datasource()->getAllUserStoriesByProject($project->getId());
        $view = $this->_view_ListStories($project, $stories);

        return $view;
    }

    public function CreateStory($projectID)
    {
        // display edit story form with project prefilled
        $project = $this->datasource()->getProject($projectID);
        $story = new UserStory;
        $story->setProject($project->getId());

        $view = $this->_view_EditStory($project, $story);
        if ($this->_request->isPost())
        {
            $view->updateModelFromRequest($this->_request);
            $this->datasource()->saveUserStory($view->getStory());

            return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'ViewStories', array($project->getId()))));
        }

        return $view;
    }

    public function EditStory($storyID)
    {
        $story = $this->datasource()->getUserStory($storyID);
        $project = $this->datasource()->getProject($story->getProject());
        $view = $this->_view_EditStory($project, $story);

        if ($this->_request->isPost())
        {
            $view->updateModelFromRequest($this->_request);
            $this->datasource()->saveUserStory($story);

            return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'ViewStories', array($project->getId()))));
        }

        return $view;
    }

    public function CreateProject()
    {
        $project = new Project();
        $view = $this->_view_EditProject($project);

        if ($this->_request->isPost())
        {
            $view->updateModelFromRequest($this->_request);
            $this->datasource()->saveProject($project);

            return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'ViewStories', array($project->getId()))));
        }
        return $view;
    }

    public function CreateIteration($projectID)
    {
        $iteration = new Iteration();
        $iteration->setProject($projectID);

        $view = $this->_view_EditIteration($iteration);
        if ($this->_request->isPost())
        {
            $view->updateFromRequest($this->_request);
            $this->datasource()->saveIteration($view->getIteration());
            return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'ViewStories', array($view->getIteration()->getProject()))));
        }

        return $view;
    }

    public function EditIteration($iterationID)
    {
        $iteration = $this->datasource()->getIteration($iterationID);
        $view = $this->_view_EditIteration($iteration);
        if ($this->_request->isPost())
        {
            $view->updateFromRequest($this->_request);
            $this->datasource()->saveIteration($view->getIteration());
            return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'ViewStories', array($view->getIteration()->getProject()))));
        }

        return $view;
    }

    public function EditProject($projectID)
    {
        $project = $this->datasource()->getProject($projectID);
        $view = $this->_view_EditProject($project);

        if ($this->_request->isPost())
        {
            $view->updateModelFromRequest($this->_request);
            $this->datasource()->saveProject($view->getProject());

            return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'ViewStories', array($project->getId()))));
        }

        return $view;
    }

    public function DeleteProject($projectID)
    {
        if ($this->_request->isPost())
            $this->datasource()->deleteProjectByID($projectID);

        return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
    }

    public function DeleteStory($storyID)
    {
        $story = $this->datasource()->getUserStory($storyID);

        if ($this->_request->isPost())
            $this->datasource()->deleteStoryByID($storyID);

        return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'ViewStories', array($story->getProject()))));
    }

    protected function datasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new ProjectManagementDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    protected function _view_SearchResults()
    {
        /**
         * @var StorySearchResultsView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Views\\StorySearchResultsView');

        return $view;
    }

    protected function _view_Search()
    {
        /**
         * @var StorySearchView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Views\\StorySearchView');

        $view->setSearch(new UserStorySearch());
        $view->setProjects($this->datasource()->getAllProjects());
        $view->setSearchURI($this->_router->getURI(new Route(__CLASS__, 'Search')));

        return $view;
    }

    protected function _view_EditProject(Project $project)
    {
        /**
         * @var EditProjectView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Views\\EditProjectView');
        $view->setProject($project);

        if ($project->getId() > 0)
            $view->setSaveURI($this->_router->getURI(new Route(__CLASS__, 'EditProject', array($project->getId()))));
        else
            $view->setSaveURI($this->_router->getURI(new Route(__CLASS__, 'CreateProject')));

        $view->setBreadcrumbs(array
        (
            'Project Listing' => $this->_router->getURI(new Route(__CLASS__)),
            $project->getName() => $this->_router->getURI(new Route(__CLASS__, 'ViewStories', array($project->getId()))),
            'Edit' => ''
        ));

        $this->_dependencies->applyDependencies($view);

        return $view;
    }

    protected function _view_EditStory(Project $project, UserStory $story)
    {
        $guid = $this->datasource()->getStoryGUID($story->getId());
        $returnURI = $this->_router->getURI(new Route(__CLASS__, 'EditStory', array($story->getId())));
        $commentsView = $this->_commentsManager->getCommentsViewForObject($guid, $returnURI);
        $timeLogView = $this->_timeTracker->getTimeLogViewForObject($guid, $returnURI);

        /**
         * @var EditStoryView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Views\\EditStoryView');
        $view->setProject($project);
        $view->setStory($story);
        $view->setCommentsView($commentsView);
        $view->setTimeLogView($timeLogView);

        if ($story->getId() > 0)
            $view->setSaveURI($this->_router->getURI(new Route(__CLASS__, 'EditStory', array($story->getId()))));
        else
            $view->setSaveURI($this->_router->getURI(new Route(__CLASS__, 'CreateStory', array($project->getId()))));

        $view->setBreadcrumbs(array
        (
            'Project Listing' => $this->_router->getURI(new Route(__CLASS__)),
            $project->getName() => $this->_router->getURI(new Route(__CLASS__, 'ViewStories', array($project->getId()))),
            $story->getName() => '',
        ));

        $this->_dependencies->applyDependencies($view);

        return $view;
    }

    /**
     * @param Project[] $projects
     * @return \CannyDain\ShortyCoreModules\ProjectManagement\Views\ListProjectsView
     */
    protected function _view_ListProjects($projects)
    {
        /**
         * @var ListProjectsView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Views\\ListProjectsView');
        $view->setCreateProjectURI($this->_router->getURI(new Route(__CLASS__, 'CreateProject')));
        $view->setDeleteProjectURITemplate($this->_router->getURI(new Route(__CLASS__, 'DeleteProject', array('#id#'))));
        $view->setEditProjectURITemplate($this->_router->getURI(new Route(__CLASS__, 'EditProject', array('#id#'))));
        $view->setViewProjectURITemplate($this->_router->getURI(new Route(__CLASS__, 'ViewStories', array('#id#'))));
        $view->setSearchView($this->_view_Search());

        $view->setProjects($projects);
        $view->setBreadcrumbs(array
        (
            'Project Listing' => ''
        ));

        $this->_dependencies->applyDependencies($view);

        return $view;
    }

    /**
     * @param Iteration $iteration
     * @return \CannyDain\ShortyCoreModules\ProjectManagement\Views\EditIteration
     */
    protected function _view_EditIteration(Iteration $iteration)
    {
        /**
         * @var EditIteration $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Views\\EditIteration');

        $view->setIteration($iteration);

        if ($iteration->getId() > 0)
            $view->setSaveURI($this->_router->getURI(new Route(__CLASS__, 'EditIteration', array($iteration->getId()))));
        else
            $view->setSaveURI($this->_router->getURI(new Route(__CLASS__, 'CreateIteration', array($iteration->getProject()))));

        return $view;
    }

    /**
     * @param Project $project
     * @param UserStory[] $stories
     * @return \CannyDain\ShortyCoreModules\ProjectManagement\Views\ListStoriesView
     */
    protected function _view_ListStories(Project $project, $stories)
    {
        /**
         * @var ListStoriesView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Views\\ListStoriesView');
        $view->setProject($project);
        $view->setStories($stories);

        $view->setCreateStoryURI($this->_router->getURI(new Route(__CLASS__, 'CreateStory', array($project->getId()))));
        $view->setEditStoryURITemplate($this->_router->getURI(new Route(__CLASS__, 'EditStory', array('#id#'))));
        $view->setDeleteStoryURITemplate($this->_router->getURI(new Route(__CLASS__, 'DeleteStory', array('#id#'))));
        $view->setCommentsView($this->_commentsManager->getCommentsViewForObject($this->datasource()->getProjectGUID($project->getId()), $this->_router->getURI(new Route(__CLASS__, 'ViewStories', array($project->getId())))));
        $view->setIterations($this->datasource()->getAllIterations($project->getId()));
        $view->setEditIterationURITemplate($this->_router->getURI(new Route(__CLASS__, 'EditIteration', array('#id#'))));
        $view->setCreateIterationURI($this->_router->getURI(new Route(__CLASS__, 'CreateIteration', array($project->getId()))));
        $view->setRecommendedStories($this->datasource()->getRecommendedStories($project->getId(), 5));
        $view->setIterationStatsURI($this->_router->getURI(new Route(__CLASS__, 'IterationStats', array($project->getId()))));

        $view->setBreadcrumbs(array
        (
            'Project Listing' => $this->_router->getURI(new Route(__CLASS__)),
            $project->getName() => ''
        ));

        $this->_dependencies->applyDependencies($view);

        return $view;
    }

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return true;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeViewFactory(ViewFactory $dependency)
    {
        $this->_viewFactory = $dependency;
    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }

    public function consumeCommentsManager(CommentsManager $manager)
    {
        $this->_commentsManager = $manager;
    }

    public function consumeTimeTracker(TimeTracker $dependency)
    {
        $this->_timeTracker = $dependency;
    }
}