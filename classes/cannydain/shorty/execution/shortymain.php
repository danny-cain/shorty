<?php

namespace CannyDain\Shorty\Execution;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Exceptions\CannyLibException;
use CannyDain\Lib\Execution\Exceptions\NotAuthorisedException;
use CannyDain\Lib\Execution\Interfaces\ControllerFactoryInterface;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\UI\Response\Layouts\NullLayout;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Config\ShortyConfiguration;
use CannyDain\Shorty\Consumers\ControllerFactoryConsumer;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouteAccessControlConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Shorty\Exceptions\ControllerNotFoundException;
use CannyDain\Shorty\Exceptions\MethodNotFoundException;
use CannyDain\Shorty\Exceptions\RoutingException;
use CannyDain\Shorty\RouteAccessControl\RouteAccessControlInterface;
use CannyDain\Shorty\UI\FramedViewLayout;
use CannyDain\Shorty\UI\ShortyLayout;
use CannyDain\Shorty\Views\Errors\ExceptionView;
use CannyDain\Shorty\Views\Errors\NotAuthorisedView;
use CannyDain\Shorty\Views\Errors\PageNotFoundView;

class ShortyMain implements AppMainInterface, DependencyConsumer, RouterConsumer, RequestConsumer, ControllerFactoryConsumer, RouteAccessControlConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var RouteAccessControlInterface
     */
    protected $_routeAccessControl;

    /**
     * @var ControllerFactoryInterface
     */
    protected $_controllerFactory;

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var Request
     */
    protected $_request;

    public function main()
    {
        try
        {
            $view = $this->_executeRouteAndGetView();
        }
        catch(RoutingException $e)
        {
            $view = new PageNotFoundView($e);
        }
        catch(NotAuthorisedException $e)
        {
            $view = new NotAuthorisedView($e);
        }
        catch(\Exception $e)
        {
            $view = new ExceptionView($e);
        }

        $layout = $this->_layoutFactory($view);

        $this->_dependencies->applyDependencies($view);
        $this->_dependencies->applyDependencies($layout);

        $layout->display($view);
    }

    protected function _layoutFactory(ViewInterface $view)
    {
        if ($view->getContentType() != 'text/html')
        {
            return new NullLayout($view->getContentType());
        }

        if ($view instanceof HTMLView && $view->getIsAjax())
        {
            return new NullLayout($view->getContentType());
        }

        if ($view instanceof HTMLView && $view->getIsFramed())
        {
            return new FramedViewLayout();
        }

        return new ShortyLayout();
    }

    protected function _getDefaultPage()
    {
        return '/cannydain-shorty-controllers-shortyexamplecontroller';
    }

    /**
     *
     * @throws \CannyDain\Lib\Execution\Exceptions\NotAuthorisedException
     * @throws \CannyDain\Shorty\Exceptions\MethodNotFoundException
     * @throws \CannyDain\Shorty\Exceptions\RoutingException
     * @return ViewInterface
     */
    protected function _executeRouteAndGetView()
    {
        $resource = $this->_request->getResource();
        if ($resource == '')
            $resource = $this->_getDefaultPage();

        $route = $this->_router->getRoute($resource);
        if ($route == null)
            return new PageNotFoundView;

        if ($route->getMethod() == '')
            $route->setMethod('Index');

        if (!$this->_routeAccessControl->canAccessRoute($route))
            throw new NotAuthorisedException();

        $controller = $this->_controllerFactory->getControllerByName($route->getController());

        if (!method_exists($controller, $route->getMethod()))
            throw new MethodNotFoundException($route);

        $this->_dependencies->applyDependencies($controller);

        if ($controller instanceof ShortyController)
            $controller->_validateState();

        $view = call_user_func_array(array($controller, $route->getMethod()), $route->getParams());
        if (!$view instanceof ViewInterface)
            throw new RoutingException($route, 'Invalid View');

        return $view;
    }

    public function consumeDependencies(DependencyInjector $dependencies)
    {
        $this->_dependencies = $dependencies;
    }

    public function consumeRouter(RouterInterface $router)
    {
        $this->_router = $router;
    }

    public function consumeRequest(Request $request)
    {
        $this->_request = $request;
    }

    public function consumeControllerFactory(ControllerFactoryInterface $controllerFactory)
    {
        $this->_controllerFactory = $controllerFactory;
    }

    public function consumeRouteAccessControl(RouteAccessControlInterface $rac)
    {
        $this->_routeAccessControl = $rac;
    }
}