<?php

namespace CannyDain\ShortyModules\Users\Models;

use CannyDain\Shorty\Models\ShortyModel;

class Session extends ShortyModel
{
    const SESSION_OBJECT_NAME = __CLASS__;

    protected $_id = 0;
    protected $_uid = 0;
    protected $_sessionStart = 0;
    protected $_active = true;

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        if ($this->_sessionStart == 0)
            $this->_sessionStart = time();

        return array();
    }

    public function save()
    {
        if ($this->_sessionStart == 0)
            $this->_sessionStart = time();

        parent::save();
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setActive($active)
    {
        $this->_active = $active;
    }

    public function getActive()
    {
        return $this->_active;
    }

    public function setSessionStart($sessionStart)
    {
        $this->_sessionStart = $sessionStart;
    }

    public function getSessionStart()
    {
        return $this->_sessionStart;
    }

    public function setUid($uid)
    {
        $this->_uid = $uid;
    }

    public function getUid()
    {
        return $this->_uid;
    }
}