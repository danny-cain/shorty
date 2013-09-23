<?php

namespace CannyDain\Shorty\Helpers\Models;

class UserInfo
{
    protected $_id = 0;
    protected $_guid = '';
    protected $_name = '';

    function __construct($_guid, $_id, $_name)
    {
        $this->_guid = $_guid;
        $this->_id = $_id;
        $this->_name = $_name;
    }

    public function setGuid($guid)
    {
        $this->_guid = $guid;
    }

    public function getGuid()
    {
        return $this->_guid;
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
}