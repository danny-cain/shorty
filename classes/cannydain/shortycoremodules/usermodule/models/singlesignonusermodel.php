<?php

namespace CannyDain\ShortyCoreModules\UserModule\Models;

abstract class SingleSignOnUserModel extends UserModel
{
    public function checkPassword($password)
    {
        return false;
    }

    public function changePassword($newPassword)
    {

    }

    public function setHashedPassword($hashedPassword)
    {

    }

    public function getHashedPassword()
    {
        return '';
    }

    public function setUsername($username)
    {

    }

}