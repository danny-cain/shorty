<?php

namespace CannyDain\ShortyModules\Todo\Views;

use CannyDain\Lib\CommentsManager\CommentsManager;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\CommentsConsumer;
use CannyDain\Shorty\Helpers\Forms\FormHelperInterface;
use CannyDain\Shorty\Helpers\Forms\Models\SubmitButton;
use CannyDain\Shorty\Helpers\Forms\Models\TextboxField;
use CannyDain\Shorty\Views\ShortyFormView;
use CannyDain\ShortyModules\Todo\Models\TodoEntry;

class TodoEditView extends ShortyFormView implements CommentsConsumer
{
    /**
     * @var TodoEntry
     */
    protected $_entry;

    /**
     * @var CommentsManager
     */
    protected $_comments;

    /**
     * @var Route
     */
    protected $_saveRoute;

    /**
     * @var Route
     */
    protected $_viewRoute;

    protected $_formSetup = false;

    /**
     * @return bool
     */
    public function updateFromPostAndReturnTrueIfPostedAndValid()
    {
        if (!$this->_request->isPost())
            return false;

        $this->_setupForm();
        $this->_formHelper->updateFromRequest($this->_request);
        $this->_entry->setTitle($this->_formHelper->getField(TodoEntry::FIELD_TITLE)->getValue());
        $this->_entry->setInfo($this->_formHelper->getField(TodoEntry::FIELD_INFO)->getValue());

        $errors = $this->_entry->validateAndReturnErrors();
        foreach ($errors as $field => $msg)
            $this->_formHelper->getField($field)->setErrorText($msg);

        return count($errors) == 0;
    }

    public function display()
    {
        $this->_setupForm();

        echo '<h1>Add/Edit Task</h1>';
        $this->_formHelper->displayForm();

        $this->_displayComments();
    }

    protected function _displayComments()
    {
        if ($this->_viewRoute == null)
            return;

        if ($this->_entry->getGUID() == null)
            return;

        $view = $this->_comments->getCommentsViewForObject($this->_entry->getGUID(), $this->_router->getURI($this->_viewRoute));
        if ($view == null)
            return;

        $view->display();
    }

    protected function _setupForm()
    {
        if ($this->_formSetup)
            return;

        $this->_formSetup = true;
        $this->_formHelper->setMethod(FormHelperInterface::FORM_METHOD_POST)
                          ->setURI($this->_router->getURI($this->_saveRoute))
                          ->addField(new TextboxField('Title', TodoEntry::FIELD_TITLE, $this->_entry->getTitle(), 'The title / subject of this task'))
                          ->addField(new TextboxField('Info', TodoEntry::FIELD_INFO, $this->_entry->getInfo(), 'Any additional info about this task'))
                          ->addField(new SubmitButton('Save'));
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $viewRoute
     */
    public function setViewRoute($viewRoute)
    {
        $this->_viewRoute = $viewRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getViewRoute()
    {
        return $this->_viewRoute;
    }

    /**
     * @param \CannyDain\ShortyModules\Todo\Models\TodoEntry $entry
     */
    public function setEntry($entry)
    {
        $this->_entry = $entry;
    }

    /**
     * @return \CannyDain\ShortyModules\Todo\Models\TodoEntry
     */
    public function getEntry()
    {
        return $this->_entry;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $saveRoute
     */
    public function setSaveRoute($saveRoute)
    {
        $this->_saveRoute = $saveRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getSaveRoute()
    {
        return $this->_saveRoute;
    }

    public function consumeCommentsManager(CommentsManager $manager)
    {
        $this->_comments = $manager;
    }
}