<?php

namespace CannyDain\Shorty\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\UI\ViewFactory;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Consumers\ViewFactoryConsumer;

abstract class ShortyController implements ControllerInterface, DependencyConsumer, ViewFactoryConsumer, RouterConsumer, RequestConsumer
{
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
    /**
     * @var Request
     */
    protected $_request;

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return false;
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

    public function consumeViewFactory(ViewFactory $dependency)
    {
        $this->_viewFactory = $dependency;
    }
}