<?php

namespace CannyDain\Lib\DataMapping\Models;

class LinkDefinition
{
    protected $_object1 = '';
    protected $_object2 = '';
    protected $_linkTableName = '';

    /**
     * @var LinkFieldDefinition[]
     */
    protected $_fields = array();

    function __construct($_object1 = '', $_object2 = '', $_linkTableName = '', $_fields = array())
    {
        $this->_fields = $_fields;
        $this->_linkTableName = $_linkTableName;
        $this->_object1 = $_object1;
        $this->_object2 = $_object2;
    }

    public function addLinkField(LinkFieldDefinition $def)
    {
        $this->_fields[] = $def;
    }

    public function setFields($fields)
    {
        $this->_fields = $fields;
    }

    public function getFields()
    {
        return $this->_fields;
    }

    public function setLinkTableName($linkTableName)
    {
        $this->_linkTableName = $linkTableName;
    }

    public function getLinkTableName()
    {
        return $this->_linkTableName;
    }

    public function setObject1($object1)
    {
        $this->_object1 = $object1;
    }

    public function getObject1()
    {
        return $this->_object1;
    }

    public function setObject2($object2)
    {
        $this->_object2 = $object2;
    }

    public function getObject2()
    {
        return $this->_object2;
    }
}