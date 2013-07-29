<?php

namespace CannyDain\ShortyCoreModules\UserModule\Models;

class UserModel
{
    protected $_id = 0;
    protected $_username = '';
    protected $_hashedPassword = '';
    protected $_isAdmin = 0;
    protected $_registrationDate = 0;
    protected $_type = '';
    protected $_externalID = '';

    public function setExternalID($externalID)
    {
        $this->_externalID = $externalID;
    }

    public function getExternalID()
    {
        return $this->_externalID;
    }

    public function setType($type)
    {
        $this->_type = $type;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function setRegistrationDate($registrationDate)
    {
        $this->_registrationDate = $registrationDate;
    }

    public function getRegistrationDate()
    {
        return $this->_registrationDate;
    }

    public function changePassword($newPassword)
    {
        $this->_hashedPassword = $this->_hashPass($newPassword);
    }

    public function checkPassword($password)
    {
        return $this->_hashedPassword == $this->_hashPass($password);
    }

    protected function _hashPass($pass)
    {
        return md5('__SHRTY__'.$pass.'__SHRTY__');
    }

    public function setHashedPassword($hashedPassword)
    {
        $this->_hashedPassword = $hashedPassword;
    }

    public function getHashedPassword()
    {
        return $this->_hashedPassword;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setIsAdmin($isAdmin)
    {
        $this->_isAdmin = $isAdmin;
    }

    public function getIsAdmin()
    {
        return $this->_isAdmin;
    }

    public function setUsername($username)
    {
        $this->_username = $username;
    }

    public function getUsername()
    {
        return $this->_username;
    }
}