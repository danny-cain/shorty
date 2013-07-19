<?php

namespace CannyDain\ShortyCoreModules\UserModule\Managers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\UI\Response\Response;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\ResponseConsumer;
use CannyDain\Shorty\UserControl\Interfaces\SessionManager;
use CannyDain\Shorty\UserControl\Interfaces\UserManager;
use CannyDain\ShortyCoreModules\UserModule\DataAccess\UserModuleDataLayer;
use CannyDain\ShortyCoreModules\UserModule\Models\SessionModel;
use CannyDain\ShortyCoreModules\UserModule\Models\UserModel;

class UserAndSessionManager implements SessionManager, UserManager, RequestConsumer, ResponseConsumer, DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var Response
     */
    protected $_response;

    /**
     * @var SessionModel
     */
    protected $_session;

    /**
     * @var UserModel
     */
    protected $_user;

    public function initialise()
    {
        // timeouts
        $this->datasource()->timeoutSessions();

        // load session and user
        $sid = $this->_request->getCookie('sid');
        $session = $this->datasource()->getSessionByID($sid);

        if ($session == null || $session->getValid() != 1)
        {
            $session = new SessionModel();
            $session->setStarted(time());
        }
        $session->setLastActive(time());
        $this->datasource()->saveSession($session);

        $user = $this->datasource()->getUserByID($session->getUser());
        if ($user == null)
            $user = new UserModel();

        $this->_response->setCookie('sid', $session->getId(), '/');
        $this->_session = $session;
        $this->_user = $user;
    }

    public function getCurrentSessionID()
    {
        return $this->_session->getId();
    }

    public function getCurrentUserID()
    {
        return $this->_session->getUser();
    }

    public function setCurrentUserID($id)
    {
        $this->_session->setUser($id);
        $this->datasource()->saveSession($this->_session);
    }

    public function getUsernameFromID($id)
    {
        $user = $this->datasource()->getUserByID($id);
        if ($user == null)
            return '--';

        return $user->getUsername();
    }

    /**
     * @param $userID
     * @return bool
     */
    public function isAdministrator($userID)
    {
        $user = $this->datasource()->getUserByID($userID);
        if ($user == null)
            return false;

        return $user->getIsAdmin() == 1;
    }

    /**
     * @param string $username
     * @return string
     */
    public function getIDFromUsername($username)
    {
        $user = $this->datasource()->getUserByUsername($username);
        if ($user == null)
            return null;

        return $user->getId();
    }

    /**
     * @param $user
     * @param $pass
     * @return bool
     */
    public function attemptLogin($user, $pass)
    {
        $user = $this->datasource()->getUserByUsername($user);
        if ($user == null)
            return false;

        if ($user->checkPassword($pass))
        {
            $this->setCurrentUserID($user->getId());
            return true;
        }
        return false;
    }


    protected function datasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new UserModuleDataLayer();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function dependenciesConsumed()
    {
        // TODO: Implement dependenciesConsumed() method.
    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }

    public function consumeResponse(Response $dependency)
    {
        $this->_response = $dependency;
    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }
}