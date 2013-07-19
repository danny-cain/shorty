<?php

namespace CannyDain\ShortyCoreModules\UserModule\Models;

class GroupModel
{
    protected $_id = 0;
    protected $_name = '';

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