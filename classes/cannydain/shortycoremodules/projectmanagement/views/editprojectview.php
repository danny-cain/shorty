<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\Project;

class EditProjectView extends HTMLView implements FormHelperConsumer
{
    /**
     * @var Project
     */
    protected $_project;
    protected $_saveURI = '';

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    public function display()
    {
        $this->_displayBreadcrumbs();
        echo '<h1>Create / Edit a project</h1>';

        $this->_formHelper->startForm($this->_saveURI);
            $this->_formHelper->editText('name', 'Name', $this->_project->getName(), 'The name of this project');
            $this->_formHelper->editText('description', 'Description', $this->_project->getDescription(), 'A brief description of the project');
            $this->_formHelper->submitButton('Save');
        $this->_formHelper->endForm();
    }

    public function updateModelFromRequest(Request $request)
    {
        $this->_project->setName($request->getParameter('name'));
        $this->_project->setDescription($request->getParameter('description'));
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

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}