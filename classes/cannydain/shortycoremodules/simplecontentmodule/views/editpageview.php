<?php

namespace CannyDain\ShortyCoreModules\SimpleContentModule\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\SimpleContentModule\Models\ContentPage;

class EditPageView extends HTMLView implements FormHelperConsumer
{
    /**
     * @var FormHelper
     */
    protected $_formHelper;

    /**
     * @var ViewInterface[]
     */
    protected $_extraWidgets = array();

    /**
     * @var ContentPage
     */
    protected $_page;

    /**
     * @var string
     */
    protected $_saveURI= '';

    /**
     * @var ViewInterface
     */
    protected $_commentsAdminView;

    public function display()
    {
        echo '<h1>Add/Edit Page</h1>';

        if ($this->_page->getId() > 0)
        {
            echo '<div><em>Editing '.$this->_page->getTitle().'</em></div>';
        }

        $this->_formHelper->startForm($this->_saveURI);
            $this->_formHelper->editText('title', 'Title', $this->_page->getTitle(), 'The title of the page (will be displayed at the top of the page when viewed)');
            $this->_formHelper->editText('friendlyid', 'Friendly ID', $this->_page->getFriendlyID(), 'A unique name for this page that will form part of the uri if no specific uri is specified (e.g. welcome-to-our-site)');
            $this->_formHelper->editText('author', 'Author Name', $this->_page->getAuthorName(), 'The name of the author of this page');
            foreach ($this->_extraWidgets as $widget)
            {
                $widget->display();
            }

            $this->_formHelper->editRichText('content', 'Content', $this->_page->getContent(), 'The page content');
            $this->_formHelper->submitButton('Save');
        $this->_formHelper->endForm();

        if ($this->_commentsAdminView != null)
            $this->_commentsAdminView->display();
    }

    /**
     * @param \CannyDain\Lib\UI\Views\ViewInterface $commentsAdminView
     */
    public function setCommentsAdminView($commentsAdminView)
    {
        $this->_commentsAdminView = $commentsAdminView;
    }

    /**
     * @return \CannyDain\Lib\UI\Views\ViewInterface
     */
    public function getCommentsAdminView()
    {
        return $this->_commentsAdminView;
    }

    public function updateModel(Request $request)
    {
        $this->_page->setTitle($request->getParameter('title'));
        $this->_page->setFriendlyID($request->getParameter('friendlyid'));
        $this->_page->setAuthorName($request->getParameter('author'));
        $this->_page->setContent($request->getParameter('content'));
    }

    public function addWidget(ViewInterface $widget)
    {
        $this->_extraWidgets[] = $widget;
    }

    /**
     * @param \CannyDain\ShortyCoreModules\SimpleContentModule\Models\ContentPage $page
     */
    public function setPage($page)
    {
        $this->_page = $page;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\SimpleContentModule\Models\ContentPage
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * @param string $saveURI
     */
    public function setSaveURI($saveURI)
    {
        $this->_saveURI = $saveURI;
    }

    /**
     * @return string
     */
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