<?php

namespace CannyDain\ShortyModules\Stories\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Helpers\Forms\Models\LargeTextField;
use CannyDain\Shorty\Helpers\Forms\Models\SubmitButton;
use CannyDain\Shorty\Helpers\Forms\Models\TextboxField;
use CannyDain\Shorty\Views\ShortyFormView;
use CannyDain\ShortyModules\Stories\Models\Chapter;
use CannyDain\ShortyModules\Stories\Models\Story;

class EditStoryView extends ShortyFormView
{
    /**
     * @var Story
     */
    protected $_story;

    /**
     * @var Route
     */
    protected $_saveRoute;

    /**
     * @var Chapter[]
     */
    protected $_chapters;

    /**
     * @var Route
     */
    protected $_editChapterRoute;
    /**
     * @var Route
     */
    protected $_addChapterRoute;

    /**
     * @return bool
     */
    public function updateFromPostAndReturnTrueIfPostedAndValid()
    {
        if (!$this->_request->isPost())
            return false;

        $this->_setupForm();

        $this->_formHelper->updateFromRequest($this->_request);
        $this->_story->setName($this->_formHelper->getField(Story::FIELD_NAME)->getValue());
        $this->_story->setDescription($this->_formHelper->getField(Story::FIELD_DESCRIPTION)->getValue());

        $errors = $this->_story->validateAndReturnErrors();
        foreach ($errors as $field => $error)
            $this->_formHelper->getField($field)->setErrorText($error);

        return count($errors) == 0;
    }

    protected function _setupForm()
    {
        if ($this->_formHelper->getField(Story::FIELD_NAME) != null)
            return;

        $this->_formHelper->setMethod('POST');
        $this->_formHelper->setURI($this->_router->getURI($this->_saveRoute));
        $this->_formHelper->addField(new TextboxField('Name', Story::FIELD_NAME, $this->_story->getName(), 'The name of this story'));
        $this->_formHelper->addField(new LargeTextField('Description', Story::FIELD_DESCRIPTION, $this->_story->getDescription(), 'The blurb for this story'));
        $this->_formHelper->addField(new SubmitButton('Save'));
    }

    public function display()
    {
        echo '<h1>Add/Edit Story</h1>';

        $this->_setupForm();
        $this->_formHelper->displayForm();

        $this->_displayChapters();
    }

    protected function _displayChapters()
    {
        if ($this->_story->getId() < 1)
            return;

        echo '<h2>Chapters</h2>';
        $createURI = $this->_router->getURI($this->_addChapterRoute);

        echo '<div class="buttonPane">';
            echo '<a href="'.$createURI.'" class="button">Add Chapter</a>';
        echo '</div>';

        foreach ($this->_chapters as $chapter)
        {
            $editURI = $this->_router->getURI($this->_editChapterRoute->getRouteWithReplacements(array('#id#' => $chapter->getId())));

            echo '<div>';
                echo '<a href="'.$editURI.'" class="button">Edit</a>';
                echo 'Chapter '.$chapter->getChapterNumber();
                echo ' - ';
                echo $chapter->getTitle();
            echo '</div>';
        }
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $addChapterRoute
     */
    public function setAddChapterRoute($addChapterRoute)
    {
        $this->_addChapterRoute = $addChapterRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getAddChapterRoute()
    {
        return $this->_addChapterRoute;
    }

    public function setChapters($chapters)
    {
        $this->_chapters = $chapters;
    }

    public function getChapters()
    {
        return $this->_chapters;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $editChapterRoute
     */
    public function setEditChapterRoute($editChapterRoute)
    {
        $this->_editChapterRoute = $editChapterRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getEditChapterRoute()
    {
        return $this->_editChapterRoute;
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

    /**
     * @param \CannyDain\ShortyModules\Stories\Models\Story $story
     */
    public function setStory($story)
    {
        $this->_story = $story;
    }

    /**
     * @return \CannyDain\ShortyModules\Stories\Models\Story
     */
    public function getStory()
    {
        return $this->_story;
    }
}