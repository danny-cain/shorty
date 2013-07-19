<?php

namespace CannyDain\Sandbox\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Sandbox\Views\ContentView;
use CannyDain\Sandbox\Views\ResponsiveExampleView;
use CannyDain\Shorty\Consumers\DependencyConsumer;

class SandboxController implements ControllerInterface, DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencyInjector;

    public function Index()
    {
        $view = new ContentView();

        $view->setTitle('Shorty Sandbox');
        $view->setContent('<p>Welcome to the Shorty-Sandbox, this is currently in progress.</p><p><a href="/cannydain-sandbox-controllers-sandboxcontroller/responsive">Responsive Layout</a></p>');

        return $view;
    }

    public function Responsive()
    {
        $view = new ResponsiveExampleView();
        $this->_dependencyInjector->applyDependencies($view);
        return $view;
    }

    public function dependenciesConsumed()
    {
        // TODO: Implement dependenciesConsumed() method.
    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencyInjector = $dependency;
    }

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return false;
    }
}