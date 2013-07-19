<?php

namespace CannyDain\ShortyCoreModules\URIManager\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Shorty\Controllers\ShortyRouteAPIController;
use CannyDain\ShortyCoreModules\URIManager\DataAccess\URIManagerDataAccess;
use CannyDain\ShortyCoreModules\URIManager\Models\URIMappingModel;
use CannyDain\ShortyCoreModules\URIManager\Router\ManagedRouter;
use CannyDain\ShortyCoreModules\URIManager\Views\EditURIView;
use CannyDain\ShortyCoreModules\URIManager\Views\ListURIView;
use CannyDain\ShortyCoreModules\URIManager\Views\TestURIView;

class URIAdminController extends ShortyController implements DependencyConsumer, RouterConsumer, RequestConsumer
{
    const CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;
    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var Request
     */
    protected $_request;

    public function Index()
    {
        return $this->ListMappings();
    }

    public function Test()
    {
        $view = new TestURIView();
        $route = null;

        $view->setUri($this->_request->getParameter('uri'));
        if ($this->_request->isPost())
        {
            $router = new ManagedRouter();
            $this->_dependencies->applyDependencies($router);
            $route = $router->getRoute($view->getUri());
        }
        $view->setRoute($route);

        return $view;
    }

    public function ListMappings($pageNumber = 1)
    {
        $view = new ListURIView();

        $view->setCreateURI($this->_router->getURI(new Route(__CLASS__, 'Create')));
        $view->setDeleteURITemplate($this->_router->getURI(new Route(__CLASS__, 'Delete', array('#id#'))));
        $view->setEditURITemplate($this->_router->getURI(new Route(__CLASS__, 'Edit', array('#id#'))));
        $view->setPaginationLinkTemplate($this->_router->getURI(new Route(__CLASS__, 'ListMappings', array('#page#'))));

        $view->setPageNum($pageNumber);
        $view->setNoPages(ceil($this->datasource()->countAllURIs() / 25));
        $view->setUris($this->datasource()->getAllURIs(25, $pageNumber));

        return $view;
    }

    public function Create()
    {
        $uri = new URIMappingModel();
        $view = $this->_view_EditURI($uri);

        if ($this->_request->isPost())
        {
            $view->updateFromRequest($this->_request);
            $this->datasource()->saveURI($view->getUri());

            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
        }

        return $view;
    }

    public function Delete($id)
    {
        if ($this->_request->isPost())
            $this->datasource()->deleteURI($id);

        return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
    }

    public function Edit($id)
    {
        $uri = $this->datasource()->getURI($id);
        $view = $this->_view_EditURI($uri);

        if ($this->_request->isPost())
        {
            $view->updateFromRequest($this->_request);
            $this->datasource()->saveURI($view->getUri());

            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
        }

        return $view;
    }

    protected function _view_EditURI(URIMappingModel $uri)
    {
        $view = new EditURIView();
        $this->_dependencies->applyDependencies($view);

        $view->setSearchControllersAPIRoute(new Route(ShortyRouteAPIController::CONTROLLER_CLASS_NAME, 'searchControllers'));
        $view->setSearchMethodsAPIRoute(new Route(ShortyRouteAPIController::CONTROLLER_CLASS_NAME, 'searchMethods'));
        $view->setListParamsAPIRoute(new Route(ShortyRouteAPIController::CONTROLLER_CLASS_NAME, 'getParametersForMethod'));

        $view->setUri($uri);
        if ($uri->getId() > 0)
            $view->setSaveURI($this->_router->getURI(new Route(__CLASS__, 'Edit', array($uri->getId()))));
        else
            $view->setSaveURI($this->_router->getURI(new Route(__CLASS__, 'Create')));

        return $view;
    }

    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new URIManagerDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return true;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }
}