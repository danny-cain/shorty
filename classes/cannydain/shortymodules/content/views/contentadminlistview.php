<?php

namespace CannyDain\ShortyModules\Content\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\ViewHelperConsumer;
use CannyDain\Shorty\Helpers\ViewHelper\Models\ActionButtonModel;
use CannyDain\Shorty\Helpers\ViewHelper\ViewHelper;
use CannyDain\Shorty\Views\ShortyView;
use CannyDain\ShortyModules\Content\Models\ContentPage;

class ContentAdminListView extends ShortyView implements ViewHelperConsumer
{
    /**
     * @var ViewHelper
     */
    protected $_viewHelper;

    /**
     * @var ContentPage[]
     */
    protected $_pages = array();

    /**
     * @var Route
     */
    protected $_editRouteTemplate;

    /**
     * @var Route
     */
    protected $_deleteRouteTemplate;

    /**
     * @var Route
     */
    protected $_createRoute;

    public function display()
    {
        echo '<h1>Content Administration</h1>';

        $this->_viewHelper->displayPageActions(array
        (
            new ActionButtonModel('Create', $this->_router->getURI($this->_createRoute))
        ));

        foreach ($this->_pages as $page)
            $this->_displayPage($page);

        $this->_viewHelper->displayPageActions(array
        (
            new ActionButtonModel('Create', $this->_router->getURI($this->_createRoute))
        ));
    }

    protected function _displayPage(ContentPage $page)
    {
        $editURI = $this->_router->getURI($this->_editRouteTemplate->getRouteWithReplacements(array('#id#' => $page->getId())));
        $deleteURI = $this->_router->getURI($this->_deleteRouteTemplate->getRouteWithReplacements(array('#id#' => $page->getId())));

        echo '<div style="border-bottom: 1px solid #ccc; padding: 5px 0; margin: 5px 0; ">';
            echo '<div style="display: inline-block; width: 20%; vertical-align: top; ">';
                echo $page->getTitle();
            echo '</div>';

            $this->_viewHelper->displayItemActions(array
            (
                new ActionButtonModel('Edit', $editURI),
                new ActionButtonModel('Delete', $deleteURI, ActionButtonModel::ACTION_POST, 'Are you sure you wish to delete this page?'),
            ));
        echo '</div>';
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $createRoute
     */
    public function setCreateRoute($createRoute)
    {
        $this->_createRoute = $createRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getCreateRoute()
    {
        return $this->_createRoute;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $deleteRouteTemplate
     */
    public function setDeleteRouteTemplate($deleteRouteTemplate)
    {
        $this->_deleteRouteTemplate = $deleteRouteTemplate;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getDeleteRouteTemplate()
    {
        return $this->_deleteRouteTemplate;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $editRouteTemplate
     */
    public function setEditRouteTemplate($editRouteTemplate)
    {
        $this->_editRouteTemplate = $editRouteTemplate;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getEditRouteTemplate()
    {
        return $this->_editRouteTemplate;
    }

    /**
     * @param \CannyDain\ShortyModules\Content\Models\ContentPage[] $pages
     */
    public function setPages($pages)
    {
        $this->_pages = $pages;
    }

    /**
     * @return \CannyDain\ShortyModules\Content\Models\ContentPage[]
     */
    public function getPages()
    {
        return $this->_pages;
    }

    public function consumeViewHelper(ViewHelper $viewHelper)
    {
        $this->_viewHelper = $viewHelper;
    }
}