<?php

namespace CannyDain\Shorty\UserControl;

use CannyDain\Shorty\UserControl\Interfaces\SessionManager;
use CannyDain\Shorty\UserControl\Interfaces\UserManager;

class UserControl
{
    /**
     * @var SessionManager
     */
    protected $_sessionManager;

    /**
     * @var UserManager
     */
    protected $_userManager;

    public function getUserIDFromUsername($username)
    {
        if ($this->_userManager == null)
            return null;

        return $this->_userManager->getIDFromUsername($username);
    }

    public function getCurrentSessionID()
    {
        if ($this->_sessionManager == null)
            return 0;

        return $this->_sessionManager->getCurrentSessionID();
    }

    public function getCurrentUserID()
    {
        if ($this->_sessionManager == null)
            return 0;

        return $this->_sessionManager->getCurrentUserID();
    }

    public function setCurrentUserID($id)
    {
        if ($this->_sessionManager == null)
            return;

        $this->_sessionManager->setCurrentUserID($id);
    }

    public function getUsernameFromID($id)
    {
        if ($this->_userManager == null)
            return '';

        return $this->_userManager->getUsernameFromID($id);
    }

    /**
     * @param $user
     * @param $pass
     * @return bool
     */
    public function attemptLogin($user, $pass)
    {
        if ($this->_userManager == null)
            return false;

        return $this->_userManager->attemptLogin($user, $pass);
    }

    /**
     * @param $userID
     * @return bool
     */
    public function isAdministrator($userID)
    {
        if ($this->_userManager == null)
            return false;

        return $this->_userManager->isAdministrator($userID);
    }

    /**
     * @param \CannyDain\Shorty\UserControl\Interfaces\SessionManager $sessionManager
     */
    public function setSessionManager($sessionManager)
    {
        $this->_sessionManager = $sessionManager;
    }

    /**
     * @param \CannyDain\Shorty\UserControl\Interfaces\UserManager $userManager
     */
    public function setUserManager($userManager)
    {
        $this->_userManager = $userManager;
    }
}