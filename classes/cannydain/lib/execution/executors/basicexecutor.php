<?php

namespace CannyDain\Lib\Execution\Executors;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Exceptions\ControllerNotFoundException;
use CannyDain\Lib\Execution\Exceptions\MethodNotFoundException;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Execution\Interfaces\RouteExecutor;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\ViewInterface;

class BasicExecutor implements RouteExecutor
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencyInjector;

    public function __construct(DependencyInjector $dependencyInjector = null)
    {
        $this->_dependencyInjector = $dependencyInjector;
    }

    /**
     * @param Route $route
     * @throws \CannyDain\Lib\Execution\Exceptions\ControllerNotFoundException
     * @throws \CannyDain\Lib\Execution\Exceptions\MethodNotFoundException
     * @return ViewInterface
     */
    public function executeRouteAndReturnView(Route $route)
    {
        $controller = $this->_controllerFactory($route->getController());
        $this->_checkAccessRightsForController($controller);

        $this->_dependencyInjector->applyDependencies($controller);

        if (!method_exists($controller, $route->getMethod()))
            throw new MethodNotFoundException;

        $view = call_user_func_array(array($controller, $route->getMethod()), $route->getParams());
        return $view;
    }

    protected function _checkAccessRightsForController(ControllerInterface $controller)
    {

    }

    /**
     * @param $controllerName
     * @return ControllerInterface
     * @throws \CannyDain\Lib\Execution\Exceptions\ControllerNotFoundException
     */
    protected function _controllerFactory($controllerName)
    {
        if (!class_exists($controllerName))
            throw new ControllerNotFoundException;

        return new $controllerName();
    }
}