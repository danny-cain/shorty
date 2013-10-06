<?php

namespace CannyDain\Lib\DataMapping\Models;

class LinkFieldDefinition
{
    protected $_object = '';
    protected $_objectField = '';
    protected $_linkName = '';

    function __construct($_object = '', $_objectField = '', $_linkName = '')
    {
        $this->_object = $_object;
        $this->_objectField = $_objectField;
        $this->_linkName = $_linkName;
    }

    public function setLinkName($linkName)
    {
        $this->_linkName = $linkName;
    }

    public function getLinkName()
    {
        return $this->_linkName;
    }

    public function setObject($object)
    {
        $this->_object = $object;
    }

    public function getObject()
    {
        return $this->_object;
    }

    public function setObjectField($objectField)
    {
        $this->_objectField = $objectField;
    }

    public function getObjectField()
    {
        return $this->_objectField;
    }
}