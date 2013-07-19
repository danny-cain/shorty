<?php

namespace CannyDain\Shorty\Execution;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Exceptions\CannyLibException;
use CannyDain\Lib\Execution\Exceptions\ControllerNotFoundException;
use CannyDain\Lib\Execution\Exceptions\MethodNotFoundException;
use CannyDain\Lib\Execution\Exceptions\NotAuthorisedException;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\UI\Response\Response;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\ECommerceConsumer;
use CannyDain\Shorty\ECommerce\ECommerceManager;
use CannyDain\Shorty\Sidebars\SidebarManager;
use CannyDain\Shorty\UI\ShortyTemplatedDocumentFactory;
use CannyDain\Shorty\UserControl\UserControl;
use CannyDain\Shorty\Views\ExceptionView;
use CannyDain\Shorty\Views\NotAuthorisedView;
use CannyDain\Shorty\Views\NotFoundView;
use Exception;

class BaseMain implements \CannyDain\Shorty\Execution\AppMain, \CannyDain\Shorty\Consumers\RouterConsumer, \CannyDain\Shorty\Consumers\RequestConsumer, \CannyDain\Shorty\Consumers\ResponseConsumer, \CannyDain\Shorty\Consumers\DependencyConsumer, \CannyDain\Shorty\Consumers\SidebarManagerConsumer, \CannyDain\Shorty\Consumers\UserControlConsumer, ECommerceConsumer
{
    /**
     * @var ECommerceManager
     */
    protected $_ecommerce;

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var \CannyDain\Shorty\UserControl\UserControl
     */
    protected $_userManager;

    /**
     * @var \CannyDain\Lib\DependencyInjection\DependencyInjector
     */
    protected $_dependencyInjector;

    /**
     * @var \CannyDain\Shorty\Sidebars\SidebarManager
     */
    protected $_sidebarManager;

    /**
     * @var \CannyDain\Lib\UI\Response\Response
     */
    protected $_response;

    /**
     * @var Request
     */
    protected $_request;

    protected function _documentFactory()
    {
        $factory = new ShortyTemplatedDocumentFactory();
        $this->_dependencyInjector->applyDependencies($factory);

        return $factory;
    }

    protected function _setup()
    {
        $this->_ecommerce->initialise();
        $this->_setupSidebar();
    }

    protected function _setupSidebar() {}

    protected function _getHomepageURI()
    {
        return 'cannydain-shorty-controllers-shortyhomecontroller';
    }

    public function main()
    {
        $this->_setup();

        $executor = new ShortyExecutor($this->_dependencyInjector, $this->_userManager);
        $resource = $this->_request->getResource();
        if ($resource == '')
            $resource = $this->_getHomepageURI();

        $route = $this->_router->getRoute($resource);

        if ($route->getMethod() == '')
            $route->setMethod('Index');

        try
        {
            $view = $executor->executeRouteAndReturnView($route);
            if ($view == null || !($view instanceof \CannyDain\Lib\UI\Views\ViewInterface))
                throw new Exception;
        }
        catch(NotAuthorisedException $e)
        {
            $view = new NotAuthorisedView($this->_request->getResource(), $route, $e);
        }
        catch(ControllerNotFoundException $e)
        {
            $view = new NotFoundView($this->_request->getResource(), $route, $e);
        }
        catch(MethodNotFoundException $e)
        {
            $view = new NotFoundView($this->_request->getResource(), $route, $e);
        }
        catch(\Exception $e)
        {
            $view = new ExceptionView($this->_request->getResource(), $route, $e);
        }

        $this->_ecommerce->saveBasket();

        $this->_response->setView($view);

        try
        {
            $this->_response->setDocument($this->_documentFactory()->getDocumentForView($view));
        }
        catch(CannyLibException $e)
        {
            $e->display();
            return;
        }
        catch(Exception $e)
        {
            echo '<p>';
                echo $e->getMessage();
            echo '</p>';

            echo '<pre>';
                print_r($e->getTraceAsString());
            echo '</pre>';
            return;
        }

        $this->_response->display();
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }

    public function consumeResponse(Response $dependency)
    {
        $this->_response = $dependency;
    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencyInjector = $dependency;
    }

    public function consumeSidebarManager(SidebarManager $manager)
    {
        $this->_sidebarManager = $manager;
    }

    public function consumeUserController(UserControl $dependency)
    {
        $this->_userManager = $dependency;
    }

    public function consumeECommerceManager(ECommerceManager $dependency)
    {
        $this->_ecommerce = $dependency;
    }
}