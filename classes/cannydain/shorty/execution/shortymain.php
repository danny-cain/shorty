<?php

namespace CannyDain\Shorty\Execution;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Config\ShortyConfiguration;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Shorty\Exceptions\ControllerNotFoundException;
use CannyDain\Shorty\Exceptions\MethodNotFoundException;
use CannyDain\Shorty\Exceptions\RoutingException;
use CannyDain\Shorty\UI\ShortyLayout;
use CannyDain\Shorty\Views\Errors\PageNotFoundView;

class ShortyMain implements AppMainInterface, DependencyConsumer, RouterConsumer, RequestConsumer
{
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

    public function main()
    {
        try
        {
            $view = $this->_executeRouteAndGetView();
        }
        catch(\Exception $e)
        {
            $e->getMessage();
            echo '<div>'.get_class($e).'</div>';
            $view = new PageNotFoundView();
        }

        $layout = $this->_layoutFactory($view);
        $layout->display($view);
    }

    protected function _layoutFactory(ViewInterface $view)
    {
        return new ShortyLayout();
    }

    protected function _getDefaultPage()
    {
        return '/cannydain-shorty-controllers-shortyexamplecontroller';
    }

    /**
     * @return ViewInterface
     * @throws \CannyDain\Shorty\Exceptions\ControllerNotFoundException
     * @throws \CannyDain\Shorty\Exceptions\RoutingException
     * @throws \CannyDain\Shorty\Exceptions\MethodNotFoundException
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

        if (!class_exists($route->getController()))
            throw new ControllerNotFoundException($route);

        $controllerClass = $route->getController();
        $controller = new $controllerClass();
        if (!$controller instanceof ControllerInterface)
            throw new ControllerNotFoundException($route);

        if (!method_exists($controller, $route->getMethod()))
            throw new MethodNotFoundException($route);

        $this->_dependencies->applyDependencies($controller);

        if ($controller instanceof ShortyController)
            $controller->_validateState();

        $view = call_user_func_array(array($controller, $route->getMethod()), $route->getParams());
        if (!$view instanceof ViewInterface)
            throw new RoutingException($route, 'Invalid View');

        $this->_dependencies->applyDependencies($view);

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
}