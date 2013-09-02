<?php

namespace CannyDain\ShortyModules\Users\Helpers;

use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Helpers\UserHelper;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\Users\UsersModule;

class UsersModuleUserHelper extends UserHelper implements ModuleConsumer
{
    /**
     * @var UsersModule
     */
    protected $_userModule;

    public function getUsernameFromID($id)
    {
        $user = $this->_userModule->getDatasource()->loadUser($id);
        if ($user == null)
            return 'guest';

        return $user->getUsername();
    }

    public function getDisplayNameFromID($id)
    {
        return $this->getUsernameFromID($id);
    }

    public function getAllUserGuids($id)
    {
        $user = $this->_userModule->getDatasource()->loadUser($id);
        if ($user == null)
            return array();

        $ret = array($user->getGUID());

        // add group GUID's here

        return $ret;
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        $this->_userModule = $manager->getModuleByClassname(UsersModule::USERS_MODULE_CLASS);
    }
}