<?php

namespace CannyDain\ShortyModules\Users\Helpers;

use CannyDain\Lib\UI\Response\Response;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\Users\Models\Session;
use CannyDain\ShortyModules\Users\Models\User;
use CannyDain\ShortyModules\Users\UsersModule;

class UsersModuleSessionHelper extends SessionHelper implements ModuleConsumer, RequestConsumer
{
    /**
     * @var UsersModule
     */
    protected $_module;

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var Session
     */
    protected $__session;

    public function getSessionID()
    {
        return $this->_session()->getId();
    }

    public function getUserID()
    {
        return $this->_session()->getUid();
    }

    public function attemptLogin($username, $pass)
    {
        $user = $this->_datasource()->loadUserByUsername($username);
        if ($user == null)
            return false;

        if (strtolower($user->getUsername()) != strtolower($username))
            return false;

        if (!$user->checkPassword($pass))
            return false;

        $this->_session()->setUid($user->getId());
        $this->_session()->save();

        return true;
    }

    protected function _session()
    {
        if ($this->__session != null)
            return $this->__session;

        $this->__session = $this->_datasource()->loadSession($this->_request->getCookie('sid'));
        if ($this->__session == null)
            $this->__session = $this->_datasource()->createSession();

        if ($this->_session()->getActive() == false)
            $this->__session = $this->_datasource()->createSession();

        if ($this->__session->getId() == 0)
        {
            $this->__session->save();
            setcookie('sid', $this->__session->getId(), null, '/');
        }
        return $this->__session;
    }

    protected function _datasource()
    {
        return $this->_module->getDatasource();
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        $this->_module = $manager->getModuleByClassname(UsersModule::USERS_MODULE_CLASS);
    }

    public function consumeRequest(Request $request)
    {
        $this->_request = $request;
    }
}