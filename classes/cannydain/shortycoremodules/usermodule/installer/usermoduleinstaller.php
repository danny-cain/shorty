<?php

namespace CannyDain\ShortyCoreModules\UserModule\Installer;

use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Lib\Forms\Models\InputField;

class UserModuleInstaller implements ModuleInstallerInterface
{
    public function getName()
    {
        return 'Users Module';
    }

    /**
     * @return InputField[]
     */
    public function getFields()
    {
        Return array();
    }

    /**
     * @param InputField[] $fields
     * @return mixed
     */
    public function install($fields)
    {

    }
}