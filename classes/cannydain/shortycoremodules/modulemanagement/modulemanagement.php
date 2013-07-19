<?php

namespace CannyDain\ShortyCoreModules\ModuleManagement;

use CannyDain\Shorty\Modules\BaseModule;
use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Shorty\Modules\Interfaces\ModuleInterface;
use CannyDain\Shorty\Modules\Models\ModuleInfo;
use CannyDain\ShortyCoreModules\ModuleManagement\Controllers\ModuleManagementController;
use CannyDain\ShortyCoreModules\ModuleManagement\Installer\ModuleManagementInstaller;

class ModuleManagement extends BaseModule
{
    public function getAdminControllerName()
    {
        return ModuleManagementController::CONTROLLER_CLASS_NAME;
    }

    /**
     * @return ModuleInstallerInterface
     */
    public function getInstaller()
    {
        return new ModuleManagementInstaller();
    }

    public function initialise()
    {

    }

    public function enable()
    {

    }

    public function disable()
    {

    }

    /**
     * @return ModuleInfo
     */
    public function getInfo()
    {
        $info = new ModuleInfo();

        $info->setAuthor('Danny Cain');
        $info->setAuthorWebsite('www.dannycain.com');
        $info->setName('Module Management');
        $info->setReleaseDate('2013-06-19');
        $info->setVersion('1.0.0');

        return $info;
    }

    /**
     * @return array
     */
    public function getControllerNames()
    {
        return array('\\CannyDain\\ShortyCoreModules\\ModuleManagement\\Controllers\\ModuleManagementController');
    }
}