<?php

namespace CannyDain\Shorty\InstanceManager\Models;

class BaseTypeDefinition
{
    protected $_id = 0;
    protected $_interfaceOrClassName = '';
    protected $_friendlyTypeName = '';

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setFriendlyTypeName($friendlyTypeName)
    {
        $this->_friendlyTypeName = $friendlyTypeName;
    }

    public function getFriendlyTypeName()
    {
        return $this->_friendlyTypeName;
    }

    public function setInterfaceOrClassName($interfaceOrClassName)
    {
        $this->_interfaceOrClassName = $interfaceOrClassName;
    }

    public function getInterfaceOrClassName()
    {
        return $this->_interfaceOrClassName;
    }
}