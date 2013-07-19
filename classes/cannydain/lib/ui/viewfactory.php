<?php

namespace CannyDain\Lib\UI;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\UI\Exceptions\NotAViewException;
use CannyDain\Lib\UI\Exceptions\ViewNotFoundException;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use Exception;

class ViewFactory implements DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    protected $_overrides = array();

    public function overrideView($viewToReplace, $newView)
    {
        $this->_overrides[$viewToReplace] = $newView;
    }

    /**
     * @param $className
     * @param array $constructorParams
     * @throws Exceptions\ViewNotFoundException
     * @throws Exceptions\NotAViewException
     * @return ViewInterface
     */
    public function getView($className, $constructorParams = array())
    {
        if (isset($this->_overrides[$className]))
            $className = $this->_overrides[$className];

        if (!class_exists($className))
            throw new ViewNotFoundException;

        $reflectionClass = new \ReflectionClass($className);
        if ($reflectionClass->getConstructor() == null)
            $instance = $reflectionClass->newInstance();
        else
            $instance = $reflectionClass->newInstanceArgs($constructorParams);

        if (!($instance instanceof ViewInterface))
            throw new NotAViewException;

        $this->_dependencies->applyDependencies($instance);

        return $instance;

    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }
}