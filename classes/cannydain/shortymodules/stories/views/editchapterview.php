<?php

namespace CannyDain\ShortyModules\Stories\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Helpers\Forms\Models\RichtextField;
use CannyDain\Shorty\Helpers\Forms\Models\SubmitButton;
use CannyDain\Shorty\Helpers\Forms\Models\TextboxField;
use CannyDain\Shorty\Views\ShortyFormView;
use CannyDain\ShortyModules\Stories\Models\Chapter;

class EditChapterView extends ShortyFormView
{
    /**
     * @var Route
     */
    protected $_saveRoute;

    /**
     * @var Chapter
     */
    protected $_chapter;

    /**
     * @return bool
     */
    public function updateFromPostAndReturnTrueIfPostedAndValid()
    {
        if (!$this->_request->isPost())
            return false;

        $this->_setupForm();
        $this->_formHelper->updateFromRequest($this->_request);

        $this->_chapter->setTitle($this->_formHelper->getField(Chapter::FIELD_TITLE)->getValue());
        $this->_chapter->setContent($this->_formHelper->getField(Chapter::FIELD_CONTENT)->getValue());

        $errors = $this->_chapter->validateAndReturnErrors();
        foreach ($errors as $field => $error)
            $this->_formHelper->getField($field)->setErrorText($error);

        return count($errors) == 0;
    }

    protected function _setupForm()
    {
        if ($this->_formHelper->getField(Chapter::FIELD_TITLE) != null)
            return;

        $this->_formHelper->setURI($this->_router->getURI($this->_saveRoute));
        $this->_formHelper->setMethod('POST');

        $this->_formHelper->addField(new TextboxField('Title', Chapter::FIELD_TITLE, $this->_chapter->getTitle(), 'The title of this chapter'));
        $this->_formHelper->addField(new RichtextField('Content', Chapter::FIELD_CONTENT, $this->_chapter->getContent(), 'The text of this chapter'));
        $this->_formHelper->addField(new SubmitButton('Save'));
    }

    public function display()
    {
        $this->_setupForm();
        echo '<h1>Edit Chapter</h1>';
        $this->_formHelper->displayForm();
    }

    /**
     * @param \CannyDain\ShortyModules\Stories\Models\Chapter $chapter
     */
    public function setChapter($chapter)
    {
        $this->_chapter = $chapter;
    }

    /**
     * @return \CannyDain\ShortyModules\Stories\Models\Chapter
     */
    public function getChapter()
    {
        return $this->_chapter;
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
}