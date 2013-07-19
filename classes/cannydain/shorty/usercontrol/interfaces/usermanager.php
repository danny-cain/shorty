<?php

namespace CannyDain\Shorty\UserControl\Interfaces;

interface UserManager
{
    public function getUsernameFromID($id);

    /**
     * @param string $username
     * @return string
     */
    public function getIDFromUsername($username);

    /**
     * @param $user
     * @param $pass
     * @return bool
     */
    public function attemptLogin($user, $pass);

    /**
     * @param $userID
     * @return bool
     */
    public function isAdministrator($userID);
}