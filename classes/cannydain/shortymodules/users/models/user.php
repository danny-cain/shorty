<?php

namespace CannyDain\ShortyModules\Users\Models;

use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Models\ShortyGUIDModel;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\Users\UsersModule;

class User extends ShortyGUIDModel implements ModuleConsumer
{
    const USER_OBJECT_NAME = __CLASS__;

    const FIELD_EMAIL = 'email';
    const FIELD_USERNAME = 'username';
    const FIELD_PASSWORD = 'password';

    protected $_username = '';
    protected $_email = '';
    protected $_hashedPassword = '';
    protected $_isAdmin = false;

    /**
     * @var UsersModule
     */
    protected $_module;

    public function isAdmin() { return $this->_isAdmin; }
    public function grantAdminRights() { $this->_isAdmin = true; }
    public function revokeAdminRights() { $this->_isAdmin = false; }

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

        if ($this->_hashedPassword == $this->_hashPassword(''))
            $errors[self::FIELD_PASSWORD] = 'Password cannot be blank';

        if ($this->_username != '')
        {
            $user = $this->_module->getDatasource()->loadUserByUsername($this->_username);
            if ($user != null)
                $errors[self::FIELD_USERNAME] = 'This username is already taken';
        }

        if ($this->_email != '')
        {
            $user = $this->_module->getDatasource()->loadUserByEmail($this->_email);
            if ($user != null)
                $errors[self::FIELD_EMAIL] = 'This email address is already in use';
        }

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

    public function consumeModuleManager(ModuleManager $manager)
    {
        $this->_module = $manager->getModuleByClassname(UsersModule::USERS_MODULE_CLASS);
    }
}