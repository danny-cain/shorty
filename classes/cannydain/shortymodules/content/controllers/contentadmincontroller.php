<?php

namespace CannyDain\ShortyModules\Content\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Shorty\Controllers\ShortyModuleController;
use CannyDain\Shorty\RouteAccessControl\RouteAccessControlInterface;
use CannyDain\ShortyModules\Content\ContentModule;
use CannyDain\ShortyModules\Content\Views\ContentAdminListView;
use CannyDain\ShortyModules\Content\Views\EditContentView;

class ContentAdminController extends ShortyModuleController
{
    public function getDefaultMinimumAccessLevel()
    {
        return RouteAccessControlInterface::ACCESS_LEVEL_ADMIN;
    }

    public function Index()
    {
        $view = new ContentAdminListView();
        $view->setPages($this->_getModule()->getDatasource()->getAllPages());
        $view->setCreateRoute(new Route(__CLASS__, 'Create'));
        $view->setEditRouteTemplate(new Route(__CLASS__, 'Edit', array('#id#')));
        $view->setDeleteRouteTemplate(new Route(__CLASS__, 'Delete', array('#id#')));

        return $view;
    }

    public function Delete($id)
    {
        if ($this->_request->isPost())
            $this->_getModule()->getDatasource()->deletePage($id);

        return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
    }

    public function Edit($id)
    {
        $page = $this->_getModule()->getDatasource()->loadPage($id);
        $view = new EditContentView();

        $view->setPage($page);
        $view->setSaveRoute(new Route(__CLASS__, 'Edit', array($id)));
        $this->_dependencies->applyDependencies($view);

        if ($view->updateFromPostAndReturnTrueIfPostedAndValid())
        {
            $view->getPage()->save();
            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
        }

        return $view;
    }

    public function Create()
    {
        $page = $this->_getModule()->getDatasource()->createPage();
        $view = new EditContentView();

        $view->setPage($page);
        $view->setSaveRoute(new Route(__CLASS__, 'Create'));
        $this->_dependencies->applyDependencies($view);

        if ($view->updateFromPostAndReturnTrueIfPostedAndValid())
        {
            $view->getPage()->save();
            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
        }

        return $view;
    }

    protected function _getModuleClassname()
    {
        return ContentModule::CONTENT_MODULE_CLASS;
    }

    /**
     * @return ContentModule
     */
    protected function _getModule()
    {
        return parent::_getModule();
    }
}