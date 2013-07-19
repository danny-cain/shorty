<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\Installer;

use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Lib\Forms\Models\InputField;

class ProjectManagementInstaller implements ModuleInstallerInterface
{
    public function getName()
    {
        return 'Project Management';
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