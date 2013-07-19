<?php

namespace CannyDain\ShortyCoreModules\SimpleContentModule\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Exceptions\MethodNotFoundException;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\ViewFactory;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Consumers\ViewFactoryConsumer;
use CannyDain\ShortyCoreModules\SimpleContentModule\DataAccess\SimpleContentDataAccess;
use CannyDain\ShortyCoreModules\SimpleContentModule\Models\ContentPage;
use CannyDain\ShortyCoreModules\SimpleContentModule\Views\ContentPageView;
use CannyDain\ShortyCoreModules\SimpleContentModule\Views\PageListView;

class ContentController implements ControllerInterface, DependencyConsumer, RouterConsumer, ViewFactoryConsumer
{
    const CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var ViewFactory
     */
    protected $_viewFactory;

    /**
     * @var RouterInterface
     */
    protected $_router;

    public function Index()
    {
        return $this->listPagesViewFactory($this->datasource()->getAllPages());
    }

    public function View($pageID)
    {
        $page = $this->datasource()->getPageByFriendlyID($pageID);
        if ($page == null)
            $page = $this->datasource()->getPageByID($pageID);

        if ($page == null)
            throw new MethodNotFoundException;

        return $this->viewPageFactory($page, $this->datasource()->getPageGUID($page->getId()));
    }

    protected function listPagesViewFactory($pages)
    {
        /**
         * @var PageListView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\SimpleContentModule\\Views\\PageListView', array($pages));

        $view->setPages($pages);
        $view->setViewPageURITemplate($this->_router->getURI(new Route('\\CannyDain\\ShortyCoreModules\\SimpleContentModule\\Controllers\\ContentController', 'View', array('#id#'))));

        $this->_dependencies->applyDependencies($view);

        return $view;
    }

    protected function datasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new SimpleContentDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    protected function viewPageFactory(ContentPage $page, $guid)
    {
        $uri = $this->_router->getURI(new Route(__CLASS__, 'View', array($page->getFriendlyID())));

        /**
         * @var ContentPageView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\SimpleContentModule\\Views\\ContentPageView', array($page, $guid, $uri));

        $this->_dependencies->applyDependencies($view);

        return $view;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeViewFactory(ViewFactory $dependency)
    {
        $this->_viewFactory = $dependency;
    }

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return false;
    }
}