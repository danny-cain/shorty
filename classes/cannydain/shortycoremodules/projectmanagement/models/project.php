<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\Models;

class Project
{
    protected $_id = 0;
    protected $_owner = 0;
    protected $_name = '';
    protected $_description = '';

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setOwner($owner)
    {
        $this->_owner = $owner;
    }

    public function getOwner()
    {
        return $this->_owner;
    }
}