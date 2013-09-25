<?php

namespace CannyDain\ShortyModules\Finance\Models;

use CannyDain\Shorty\Models\ShortyGUIDModel;

class Account extends ShortyGUIDModel
{
    const OBJECT_TYPE_ACCOUNT = __CLASS__;

    protected $_owner = 0;
    protected $_name = '';

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

    protected function _getObjectTypeName()
    {
        return self::OBJECT_TYPE_ACCOUNT;
    }

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        return array();
    }

}