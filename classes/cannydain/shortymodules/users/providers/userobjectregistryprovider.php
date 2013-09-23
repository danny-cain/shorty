<?php

namespace CannyDain\ShortyModules\Users\Providers;

use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Lib\GUIDS\Models\ObjectInfoModel;
use CannyDain\Lib\GUIDS\ObjectRegistryProvider;
use CannyDain\Shorty\Consumers\GUIDConsumer;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\Users\Datasource\UsersDatasource;
use CannyDain\ShortyModules\Users\Models\User;
use CannyDain\ShortyModules\Users\UsersModule;

class UserObjectRegistryProvider implements ObjectRegistryProvider, ModuleConsumer, GUIDConsumer
{
    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    /**
     * @var UsersDatasource
     */
    protected $_datasource;

    /**
     * @param string $searchTerm
     * @param string $typeLimit
     * @param int $limit
     * @return ObjectInfoModel[]
     */
    public function searchObjects($searchTerm, $typeLimit = null, $limit = 0)
    {
        $ret = array();

        foreach ($this->_datasource->searchUsers($searchTerm) as $user)
        {
            $ret[] = new ObjectInfoModel($user->getId(), $user->getGUID(), $user->getUsername(), User::USER_OBJECT_NAME);
        }

        return $ret;
    }

    /**
     * @param $guid
     * @return string
     */
    public function getNameOfObject($guid)
    {
        $id = $this->_guids->getID($guid);
        $type = $this->_guids->getType($guid);

        if ($type != User::USER_OBJECT_NAME)
            return '';

        $user = $this->_datasource->loadUser($id);
        return $user->getUsername();
    }

    /**
     * @return array
     */
    public function getKnownTypes()
    {
        return array
        (
            User::USER_OBJECT_NAME,
        );
    }

    public function consumeGUIDManager(GUIDManagerInterface $guidManager)
    {
        $this->_guids = $guidManager;
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        /**
         * @var UsersModule $module
         */
        $module = $manager->getModuleByClassname(UsersModule::USERS_MODULE_CLASS);
        if ($module == null || !($module instanceof UsersModule))
            throw new \Exception("Unable to locate users module");

        $this->_datasource = $module->getDatasource();
    }
}