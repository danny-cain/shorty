<?php

namespace CannyDain\ShortyCoreModules\ShortyNavigation\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\ShortyCoreModules\ShortyNavigation\DataAccess\ShortyNavigationDataAccess;
use CannyDain\ShortyCoreModules\ShortyNavigation\Models\NavItemModel;
use CannyDain\ShortyCoreModules\ShortyNavigation\Views\EditNavItemView;
use CannyDain\ShortyCoreModules\ShortyNavigation\Views\ListNavItemsView;

class ShortyNavigationAdminController implements ControllerInterface, DependencyConsumer, RouterConsumer, RequestConsumer
{
    const CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var RouterInterface
     */
    protected $_router;

    public function Index()
    {
        return $this->ViewNavItems(0);
    }

    public function ViewNavItems($parentID)
    {
        $parentParent = -1;
        if ($parentID > 0)
        {
            $parent = $this->datasource()->getNavItemByID($parentID);
            $parentParent = $parent->getParent();
        }

        $view = $this->_view_ListNavItems($this->datasource()->getNavItemsByParent($parentID), $parentID, $parentParent);

        return $view;
    }

    public function EditNavItem($id)
    {
        $view = $this->_view_EditNavItem($this->datasource()->getNavItemByID($id));

        if ($this->_request->isPost())
        {
            $view->updateModel($this->_request);
            $this->datasource()->saveNavItem($view->getItem());
            return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'ViewNavItems', array($view->getItem()->getParent()))));
        }
        return $view;
    }

    public function CreateNavItem($parentID = 0)
    {
        $item = new NavItemModel();
        $item->setParent($parentID);

        $view = $this->_view_EditNavItem($item);

        if ($this->_request->isPost())
        {
            $view->updateModel($this->_request);
            $this->datasource()->saveNavItem($view->getItem());
            return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'ViewNavItems', array($view->getItem()->getParent()))));
        }

        return $view;
    }

    public function DeleteNavItem($id)
    {
        $item = $this->datasource()->getNavItemByID($id);

        if ($this->_request->isPost())
            $this->datasource()->deleteNavItem($id);

        return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'ViewNavItems', array($item->getParent()))));
    }

    /**
     * @param NavItemModel $item
     * @return EditNavItemView
     */
    protected function _view_EditNavItem(NavItemModel $item)
    {
        $view = new EditNavItemView();
        $view->setItem($item);

        $this->_dependencies->applyDependencies($view);

        return $view;
    }

    /**
     * @param NavItemModel[] $items
     * @param $currentParentID
     * @param $parentParentID
     * @return \CannyDain\ShortyCoreModules\ShortyNavigation\Views\ListNavItemsView
     */
    protected function _view_ListNavItems($items, $currentParentID, $parentParentID)
    {
        $view = new ListNavItemsView();
        $view->setItems($items);
        $view->setViewChildrenLinkTemplate($this->_router->getURI(new Route(__CLASS__, 'ViewNavItems', array('#id#'))));
        $view->setCreateLink($this->_router->getURI(new Route(__CLASS__, 'CreateNavItem', array($currentParentID))));
        $view->setEditLinkTemplate($this->_router->getURI(new Route(__CLASS__, 'EditNavItem', array('#id#'))));
        $view->setDeleteLinkTemplate($this->_router->getURI(new Route(__CLASS__, 'DeleteNavItem', array('#id#'))));

        $view->setBreadcrumbs($this->_getBreadcrumbsToItem($this->datasource()->getNavItemByID($currentParentID), ''));

        if ($parentParentID > -1)
            $view->setUpOneLevelLink($this->_router->getURI(new Route(__CLASS__, 'ViewNavItems', array($parentParentID))));

        $this->_dependencies->applyDependencies($view);

        return $view;
    }

    protected function _getBreadcrumbsToItem(NavItemModel $item, $linkOverride = null)
    {
        $link = $linkOverride;
        if ($linkOverride === null)
            $link = $this->_router->getURI(new Route(__CLASS__, 'ViewNavItems', array($item->getId())));

        if ($item->getParent() == 0)
        {
            $crumbs = array('Menu Administration' => $this->_router->getURI(new Route(__CLASS__)));
            if ($item->getId() > 0)
                $crumbs[$item->getCaption()] = $link;

            return $crumbs;
        }

        $crumbs = $this->_getBreadcrumbsToItem($this->datasource()->getNavItemByID($item->getParent()));
        $crumbs[] = array($item->getCaption() => $link);

        return $crumbs;
    }

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return true;
    }

    protected function datasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new ShortyNavigationDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function dependenciesConsumed()
    {

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
}