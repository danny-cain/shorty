<?php

namespace CannyDain\Lib\DependencyInjection\Models;

class DependencyDefinition
{
    protected $_interface = '';
    protected $_dependency = null;

    public function __construct($interface, $dependency = null)
    {
        $this->_interface = $interface;
        $this->_dependency = $dependency;
    }

    public function getDependency()
    {
        return $this->_dependency;
    }

    public function getInterface()
    {
        return $this->_interface;
    }
}