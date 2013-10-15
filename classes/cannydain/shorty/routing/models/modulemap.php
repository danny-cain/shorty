<?php

namespace CannyDain\Shorty\Routing\Models;

class ModuleMap
{
    protected $_moduleClassName = '';
    protected $_alias = '';
    protected $_controllerNamespace = '';

    public function __construct($module = '', $alias = '', $controllerNamespace = '')
    {
        $this->_moduleClassName = $module;
        $this->_alias = $alias;
        $this->_controllerNamespace = $controllerNamespace;
    }

    public function setAlias($alias)
    {
        $this->_alias = $alias;
    }

    public function getAlias()
    {
        return $this->_alias;
    }

    public function setControllerNamespace($controllerNamespace)
    {
        $this->_controllerNamespace = $controllerNamespace;
    }

    public function getControllerNamespace()
    {
        if (substr($this->_controllerNamespace, strlen($this->_controllerNamespace) - 1) != '\\')
            $this->_controllerNamespace .= '\\';

        return $this->_controllerNamespace;
    }

    public function setModuleClassName($moduleClassName)
    {
        $this->_moduleClassName = $moduleClassName;
    }

    public function getModuleClassName()
    {
        return $this->_moduleClassName;
    }
}