<?php

namespace CannyDain\ShortyCoreModules\SimpleContentModule\Installer;

use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Lib\Forms\Models\InputField;

class SimpleContentInstaller implements ModuleInstallerInterface
{
    public function getName()
    {
        return 'Simple Content Module';
    }

    /**
     * @param InputField[] $fields
     * @return mixed
     */
    public function install($fields)
    {

    }

    /**
     * @return InputField[]
     */
    public function getFields()
    {
        return array();
    }
}