<?php

namespace CannyDain\ShortyModules\ObjectPermissions;

use CannyDain\Shorty\Modules\Base\ShortyModule;
use CannyDain\Shorty\Modules\Models\ModuleInfoModel;
use CannyDain\ShortyModules\ObjectPermissions\Datasource\ObjectPermissionsDatasource;

class ObjectPermissionsModule extends ShortyModule
{
    const OBJECT_PERMISSIONS_MODULE_NAME = __CLASS__;
    const CONTROLLER_NAMESPACE = '\\CannyDain\\ShortyModules\\ObjectPermissions\\Controllers';

    /**
     * Allows the module to perform any initialisation actions (i.e. loading in session etc)
     * @return void
     */
    public function initialise()
    {
        // TODO: Implement initialise() method.
    }

    /**
     * @return ModuleInfoModel
     */
    public function getInfo()
    {
        return new ModuleInfoModel('Object Permissions', 'Danny Cain', '0.1');
    }

    /**
     * @return ObjectPermissionsDatasource
     */
    public function getDatasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new ObjectPermissionsDatasource();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }
}