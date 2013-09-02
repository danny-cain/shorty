<?php

namespace CannyDain\ShortyModules\Users\Models;

use CannyDain\Shorty\Models\ShortyGUIDModel;

class User extends ShortyGUIDModel
{
    const USER_OBJECT_NAME = __CLASS__;

    const FIELD_USERNAME = 'username';
    const FIELD_PASSWORD = 'password';

    protected $_username = '';
    protected $_email = '';
    protected $_hashedPassword = '';

    public function checkPassword($password)
    {
        $enteredPass = $this->_hashPassword($password);

        return $enteredPass == $this->_hashedPassword;
    }

    public function changePassword($password)
    {
        $this->_hashedPassword = $this->_hashPassword($password);
    }

    protected function _hashPassword($password)
    {
        $salt = '_SHTY_';

        return md5($salt.$password.$salt);
    }

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        $errors = array();

        if ($this->_username == '')
            $errors[self::FIELD_USERNAME] = 'Username cannot be blank';

        if ($this->_hashedPassword == '')
            $errors[self::FIELD_PASSWORD] = 'Password cannot be blank';

        return $errors;
    }

    protected function _getObjectTypeName()
    {
        return self::USER_OBJECT_NAME;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setHashedPassword($hashedPassword)
    {
        $this->_hashedPassword = $hashedPassword;
    }

    public function getHashedPassword()
    {
        return $this->_hashedPassword;
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