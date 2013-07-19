<?php

namespace CannyDain\Shorty\Modules\Interfaces;

use CannyDain\Shorty\Modules\Models\ModuleInfo;
use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;

interface ModuleInterface
{
    /**
     * @return ModuleInstallerInterface
     */
    public function getInstaller();

    public function initialise();
    public function enable();
    public function disable();

    /**
     * @return string
     */
    public function getAdminControllerName();

    /**
     * @return ModuleInfo
     */
    public function getInfo();

    /**
     * @return array
     */
    public function getControllerNames();
}