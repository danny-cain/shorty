<?php

namespace CannyDain\ShortyModules\ObjectPermissions\Manager;

use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Lib\ObjectPermissions\Interfaces\PermissionsInfoProvider;
use CannyDain\Lib\ObjectPermissions\ObjectPermissionsManagerInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\GUIDConsumer;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Consumers\UserConsumer;
use CannyDain\Shorty\Helpers\UserHelper;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\ObjectPermissions\Controllers\PermissionsAPIController;
use CannyDain\ShortyModules\ObjectPermissions\Datasource\ObjectPermissionsDatasource;
use CannyDain\ShortyModules\ObjectPermissions\Models\PermissionModel;
use CannyDain\ShortyModules\ObjectPermissions\ObjectPermissionsModule;
use CannyDain\ShortyModules\ObjectPermissions\Views\PermissionsView;
use Exception;

class ObjectPermissionsManager implements ObjectPermissionsManagerInterface, GUIDConsumer, ModuleConsumer, UserConsumer
{
    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    /**
     * @var PermissionsInfoProvider[]
     */
    protected $_providers = array();

    /**
     * @var ObjectPermissionsDatasource
     */
    protected $_datasource;

    /**
     * @var UserHelper
     */
    protected $_users;

    public function registerProvider(PermissionsInfoProvider $provider)
    {
        $this->_providers[] = $provider;
    }

    public function grant($consumerGUID, $objectGUID, $permissions)
    {
        $model = $this->_datasource->getOrCreatePermissionModel($consumerGUID, $objectGUID);
        $model->setPermissions($model->getPermissions().$permissions);
        $model->save();
    }

    public function revoke($consumerGUID, $objectGUID, $permissions)
    {
        $model = $this->_datasource->getPermissionModel($consumerGUID, $objectGUID);
        if ($model == null)
            return;

        $currentPermissions = $model->getPermissions();
        $permissionsRevoked = 0;
        for ($i = 0; $i < strlen($permissions); $i ++)
        {
            $perm = substr($permissions, $i, 1);
            if (strpos($currentPermissions, $perm) === false)
                continue;

            $permissionsRevoked ++;
            $currentPermissions = str_replace($perm, '', $currentPermissions);
        }

        if ($permissionsRevoked == 0)
            return;

        $model->setPermissions($currentPermissions);
        $model->save();
    }

    public function hasAnyOf($userGUID, $objectGUID, $permissions = array())
    {
        $guids = $this->_getGUIDsForUser($userGUID);
        foreach ($guids as $guid)
        {
            $model = $this->_datasource->getPermissionModel($guid, $objectGUID);
            if ($model == null)
                continue;

            foreach ($permissions as $perm)
            {
                if (strpos($model->getPermissions(), $perm) !== false)
                    return true;
            }
        }

        return false;
    }

    public function hasAllOf($userGUID, $objectGUID, $permissions = array())
    {
        $guids = $this->_getGUIDsForUser($userGUID);
        foreach ($guids as $guid)
        {
            $model = $this->_datasource->getPermissionModel($guid, $objectGUID);
            if ($model == null)
                continue;

            foreach ($permissions as $key => $perm)
            {
                if (strpos($model->getPermissions(), $perm) !== false)
                    unset($permissions[$key]);
            }
        }

        return count($permissions) == 0;
    }

    /**
     * @param $objectGUID
     * @param bool $canEdit
     * @return \CannyDain\Lib\UI\Views\ViewInterface
     */
    public function getPermissionsViewForObject($objectGUID, $canEdit = false)
    {
        $view = new PermissionsView();

        if ($canEdit)
        {
            $view->setSaveRoute(new Route(PermissionsAPIController::PERMISSIONS_API_CONTROLLER_NAME, 'SaveObjectPermissions', array($objectGUID)));
            $view->setSearchRoute(new Route(PermissionsAPIController::PERMISSIONS_API_CONTROLLER_NAME, 'SearchConsumers'));
        }
        else
            $view->setSaveRoute(null);

        $view->setPermissions($this->_datasource->getAllPermissionsForObject($objectGUID));
        $view->setSubjectGUID($objectGUID);
        $view->subjectIsConsumer(false);

        return $view;
    }

    protected function _getGUIDsForUser($userGUID)
    {
        $id = $this->_guids->getID($userGUID);
        $ret = $this->_users->getAllUserGuids($id);
        $ret[] = $userGUID;

        return $ret;
    }

    public function consumeGUIDManager(GUIDManagerInterface $guidManager)
    {
        $this->_guids = $guidManager;
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        $module = $manager->getModuleByClassname(ObjectPermissionsModule::OBJECT_PERMISSIONS_MODULE_NAME);
        if ($module == null)
            throw new Exception("Module not found");

        if (!($module instanceof ObjectPermissionsModule))
            throw new Exception("Module not found");

        $this->_datasource = $module->getDatasource();
    }

    public function consumerUserHelper(UserHelper $helper)
    {
        $this->_users = $helper;
    }
}