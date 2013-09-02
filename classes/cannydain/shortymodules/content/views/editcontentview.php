<?php

namespace CannyDain\ShortyModules\Content\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\Helpers\Forms\FormHelper;
use CannyDain\Shorty\Helpers\Forms\FormHelperInterface;
use CannyDain\Shorty\Helpers\Forms\Models\RichtextField;
use CannyDain\Shorty\Helpers\Forms\Models\SubmitButton;
use CannyDain\Shorty\Helpers\Forms\Models\TextboxField;
use CannyDain\Shorty\Views\ShortyFormView;
use CannyDain\ShortyModules\Content\Models\ContentPage;

class EditContentView extends ShortyFormView
{
    /**
     * @var ContentPage
     */
    protected $_page;

    /**
     * @var Route
     */
    protected $_saveRoute;

    /**
     * @return bool
     */
    public function updateFromPostAndReturnTrueIfPostedAndValid()
    {
        if (!$this->_request->isPost())
            return false;

        $this->_setupForm();
        $this->_formHelper->updateFromRequest($this->_request);
        $this->_page->setTitle($this->_formHelper->getField(ContentPage::FIELD_TITLE)->getValue());
        $this->_page->setContent($this->_formHelper->getField(ContentPage::FIELD_CONTENT)->getValue());

        $errors = $this->_page->validateAndReturnErrors();
        foreach ($errors as $field=>$error)
            $this->_formHelper->getField($field)->setErrorText($error);

        return count($errors) == 0;
    }

    public function display()
    {
        $this->_setupForm();
        echo '<h1>Create/Edit Page</h1>';
        $this->_formHelper->displayForm();
    }

    protected function _setupForm()
    {
        if ($this->_formHelper->getField(ContentPage::FIELD_TITLE) != null)
            return;

        $this->_formHelper->setURI($this->_router->getURI($this->_saveRoute))
                          ->setMethod(FormHelperInterface::FORM_METHOD_POST)
                          ->addField(new TextboxField('Title', ContentPage::FIELD_TITLE, $this->_page->getTitle(), 'The page title (appears in large print at the top of the page)'))
                          ->addField(new RichtextField('Content', ContentPage::FIELD_CONTENT, $this->_page->getContent(), 'The page content'))
                          ->addField(new SubmitButton('Save'));
    }

    /**
     * @param \CannyDain\ShortyModules\Content\Models\ContentPage $page
     */
    public function setPage($page)
    {
        $this->_page = $page;
    }

    /**
     * @return \CannyDain\ShortyModules\Content\Models\ContentPage
     */
    public function getPage()
    {
        return $this->_page;
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