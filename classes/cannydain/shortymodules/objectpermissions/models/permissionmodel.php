<?php

namespace CannyDain\ShortyModules\ObjectPermissions\Models;

use CannyDain\Shorty\Models\ShortyModel;

class PermissionModel extends ShortyModel
{
    const OBJECT_TYPE_PERMISSION = __CLASS__;

    protected $_id = 0;
    protected $_consumerGUID = '';
    protected $_objectGUID = '';
    protected $_permissions = array();

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        return array();
    }

    public function setConsumerGUID($consumerGUID)
    {
        $this->_consumerGUID = $consumerGUID;
    }

    public function getConsumerGUID()
    {
        return $this->_consumerGUID;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setObjectGUID($objectGUID)
    {
        $this->_objectGUID = $objectGUID;
    }

    public function getObjectGUID()
    {
        return $this->_objectGUID;
    }

    public function setPermissions($permissions)
    {
        $this->_permissions = $permissions;
    }

    public function getPermissions()
    {
        return $this->_permissions;
    }
}