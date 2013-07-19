<?php

namespace CannyDain\Shorty\Controllers;

use CannyDain\Lib\UI\Views\JSONView;
use CannyDain\Shorty\Consumers\InstanceManagerConsumer;
use CannyDain\Shorty\InstanceManager\InstanceManager;
use CannyDain\Shorty\InstanceManager\Models\InstanceDefinition;

class ShortyRouteAPIController extends ShortyController implements InstanceManagerConsumer
{
    const CONTROLLER_BASE = "\\CannyDain\\Lib\\Execution\\Interfaces\\ControllerInterface";
    const CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var InstanceManager
     */
    protected $_instanceManager;

    public function searchControllers()
    {
        $searchTerm = $this->_request->getParameter('query');

        $ret = array();

        $typeID = $this->_instanceManager->getTypeByInterfaceOrClassname(self::CONTROLLER_BASE)->getId();
        foreach ($this->_instanceManager->getInstancesByType($typeID) as $instance)
        {
            if (!$this->_doesInstanceMatchSearchTerm($instance, $searchTerm))
                continue;

            $ret[] = $instance->getClassName();
        }

        return new JSONView($ret);
    }

    public function searchMethods()
    {
        $controller = $this->_request->getParameter('controller');
        $searchTerm = $this->_request->getParameter('query');

        if (substr($controller, 0, 1) != '\\')
            $controller = '\\'.$controller;

        if (!class_exists($controller))
            return new JSONView(array());

        $reflectionClass = new \ReflectionClass($controller);
        if (!$reflectionClass->implementsInterface(self::CONTROLLER_BASE))
            return new JSONView(array());

        $ret = array();

        foreach ($reflectionClass->getMethods() as $method)
        {
            if (!$this->_isMethodRoutable($method))
                continue;

            if (!$this->_doesMethodMatchSearchTerm($method, $searchTerm))
                continue;

            $ret[] = $method->getName();
        }

        return new JSONView($ret);
    }

    public function getParametersForMethod()
    {
        $controller = $this->_request->getParameter('controller');
        $methodName = $this->_request->getParameter('method');

        if (substr($controller, 0, 1) != '\\')
            $controller = '\\'.$controller;

        if (!class_exists($controller))
            return new JSONView(array());

        $reflectionClass = new \ReflectionClass($controller);
        if (!$reflectionClass->implementsInterface(self::CONTROLLER_BASE))
            return new JSONView(array());

        if (!$reflectionClass->hasMethod($methodName))
            return new JSONView(array());

        $method = $reflectionClass->getMethod($methodName);
        if ($method === null)
            return new JSONView(array());

        if (!$this->_isMethodRoutable($method))
            return new JSONView(array());

        $ret = array();
        foreach ($method->getParameters() as $param)
        {
            $paramInfo = array
            (
                'name' => $param->getName(),
                'required' => !$param->isOptional(),
                'options' => array()
            );
            $ret[] = $paramInfo;
        }

        return new JSONView($ret);
    }

    protected function _doesMethodMatchSearchTerm(\ReflectionMethod $method, $searchTerm)
    {
        return strpos(strtolower($method->getName()), strtolower($searchTerm)) !== false;
    }

    protected function _isMethodRoutable(\ReflectionMethod $method)
    {
        if (substr($method->getName(), 0, 7) == 'consume')
            return false;

        if (substr($method->getName(), 0, 2) == '__')
            return false;

        if ($method->getName() == 'dependenciesConsumed')
            return false;

        if (!$method->isPublic())
            return false;

        return true;
    }

    protected function _doesInstanceMatchSearchTerm(InstanceDefinition $instance, $searchTerm)
    {
        if (strpos(strtolower($instance->getClassName()), strtolower($searchTerm)) !== false)
            return true;

        return false;
    }

    public function consumeInstanceManager(InstanceManager $dependency)
    {
        $this->_instanceManager = $dependency;
    }
}