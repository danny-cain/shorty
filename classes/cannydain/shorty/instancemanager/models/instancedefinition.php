<?php

namespace CannyDain\Shorty\InstanceManager\Models;

class InstanceDefinition
{
    protected $_id = 0;
    protected $_baseType = 0;
    protected $_className = '';

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setBaseType($baseType)
    {
        $this->_baseType = $baseType;
    }

    public function getBaseType()
    {
        return $this->_baseType;
    }

    public function setClassName($className)
    {
        $this->_className = $className;
    }

    public function getClassName()
    {
        return $this->_className;
    }
}