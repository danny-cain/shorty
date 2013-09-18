<?php

namespace CannyDain\Lib\GUIDS\Models;

class ObjectInfoModel
{
    protected $_id = 0;
    protected $_type = '';
    protected $_name = '';

    public function __construct($_id, $_name, $_type)
    {
        $this->_id = $_id;
        $this->_name = $_name;
        $this->_type = $_type;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setType($type)
    {
        $this->_type = $type;
    }

    public function getType()
    {
        return $this->_type;
    }
}