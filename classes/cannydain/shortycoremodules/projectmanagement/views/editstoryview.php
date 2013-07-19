<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\TimeTracking\Views\TimeLogView;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\Project;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\UserStory;

class EditStoryView extends HTMLView implements FormHelperConsumer
{
    /**
     * @var Project
     */
    protected $_project;

    /**
     * @var ViewInterface
     */
    protected $_timeLogView;

    /**
     * @var ViewInterface
     */
    protected $_commentsView;

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    /**
     * @var UserStory
     */
    protected $_story;

    protected $_saveURI = '';

    public function display()
    {
        $this->_displayBreadcrumbs();
        echo '<h1>Create / Edit a User Story</h1>';

        $statuses = UserStory::getAllStatusNamesByID();

        $this->_formHelper->startForm($this->_saveURI);
            $this->_formHelper->editText('name', 'Name', $this->_story->getName(), 'The name of this story');
            $this->_formHelper->editText('section', 'Section', $this->_story->getSection(), 'The section of the project that this story relates to');
            $this->_formHelper->editText('priority', 'Priority', $this->_story->getPriority(), 'The priority of this story, the higher this is, the more important the story');
            $this->_formHelper->editText('estimate', 'Estimate', $this->_story->getEstimate(), 'The estimate of how "big" this story is (usually 1, 3 or 5)');

            $this->_formHelper->editDate('started', 'Date Started', $this->_story->getDateStarted(), strtotime('2013-01-01'), strtotime('+6 months'), 'The date work was begun on this story');
            $this->_formHelper->editDate('completed', 'Date Completed', $this->_story->getDateCompleted(), strtotime('2013-01-01'), strtotime('+6 months'), 'The date this story was completed (signed off by customer etc)');

            $this->_formHelper->select('status', 'Status', $statuses, $this->_story->getStatus(), 'The current status of this story');
            $this->_formHelper->editLargeText('target', 'As a', $this->_story->getTarget(), 'The type of person who this story applies to (i.e. developer, user, admin)');
            $this->_formHelper->editLargeText('action', 'I want to', $this->_story->getAction(), 'What the person wishes to do');
            $this->_formHelper->editLargeText('reason', 'So that', $this->_story->getReason(), 'Why they want to do it');
            $this->_formHelper->submitButton('Save');
        $this->_formHelper->endForm();

        if ($this->_commentsView != null)
            $this->_commentsView->display();

        if ($this->_timeLogView != null)
            $this->_timeLogView->display();
    }

    public function updateModelFromRequest(Request $request)
    {
        $this->_story->setName($request->getParameter('name'));
        $this->_story->setSection($request->getParameter('section'));
        $this->_story->setPriority($request->getParameter('priority'));
        $this->_story->setStatus($request->getParameter('status'));
        $this->_story->setTarget($request->getParameter('target'));
        $this->_story->setAction($request->getParameter('action'));
        $this->_story->setReason($request->getParameter('reason'));
        $this->_story->setEstimate($request->getParameter('estimate'));
        $this->_story->setDateStarted($this->_getDateFromRequest($request, 'started'));
        $this->_story->setDateCompleted($this->_getDateFromRequest($request, 'completed'));
    }

    protected function _getDateFromRequest(Request $request, $field)
    {
        $date = $request->getParameter($field);
        $strDate = $date['year'].'-'.$date['month'].'-'.$date['day'];

        return strtotime($strDate);
    }
    /**
     * @param \CannyDain\Lib\UI\Views\ViewInterface $timeLogView
     */
    public function setTimeLogView($timeLogView)
    {
        $this->_timeLogView = $timeLogView;
    }

    /**
     * @return \CannyDain\Lib\UI\Views\ViewInterface
     */
    public function getTimeLogView()
    {
        return $this->_timeLogView;
    }

    /**
     * @param \CannyDain\Lib\UI\Views\ViewInterface $commentsView
     */
    public function setCommentsView($commentsView)
    {
        $this->_commentsView = $commentsView;
    }

    /**
     * @return \CannyDain\Lib\UI\Views\ViewInterface
     */
    public function getCommentsView()
    {
        return $this->_commentsView;
    }

    /**
     * @param \CannyDain\ShortyCoreModules\ProjectManagement\Models\Project $project
     */
    public function setProject($project)
    {
        $this->_project = $project;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\ProjectManagement\Models\Project
     */
    public function getProject()
    {
        return $this->_project;
    }

    public function setSaveURI($saveURI)
    {
        $this->_saveURI = $saveURI;
    }

    public function getSaveURI()
    {
        return $this->_saveURI;
    }

    public function setStory($story)
    {
        $this->_story = $story;
    }

    public function getStory()
    {
        return $this->_story;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}