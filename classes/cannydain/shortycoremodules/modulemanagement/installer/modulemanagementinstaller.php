<?php

namespace CannyDain\ShortyCoreModules\ModuleManagement\Installer;

use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Lib\Forms\Models\InputField;

class ModuleManagementInstaller implements ModuleInstallerInterface
{
    public function getName()
    {
        return 'Module Management';
    }

    /**
     * @return InputField[]
     */
    public function getFields()
    {
        return array();
    }

    /**
     * @param InputField[] $fields
     * @return mixed
     */
    public function install($fields)
    {

    }
}