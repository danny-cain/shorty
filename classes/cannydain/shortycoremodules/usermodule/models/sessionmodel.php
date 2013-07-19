<?php

namespace CannyDain\ShortyCoreModules\UserModule\Models;

class SessionModel
{
    protected $_id = 0;
    protected $_user = 0;
    protected $_started = 0;
    protected $_lastActive = 0;
    protected $_valid = 1;

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setLastActive($lastActive)
    {
        $this->_lastActive = $lastActive;
    }

    public function getLastActive()
    {
        return $this->_lastActive;
    }

    public function setStarted($started)
    {
        $this->_started = $started;
    }

    public function getStarted()
    {
        return $this->_started;
    }

    public function setUser($user)
    {
        $this->_user = $user;
    }

    public function getUser()
    {
        return $this->_user;
    }

    public function setValid($valid)
    {
        $this->_valid = $valid;
    }

    public function getValid()
    {
        return $this->_valid;
    }
}