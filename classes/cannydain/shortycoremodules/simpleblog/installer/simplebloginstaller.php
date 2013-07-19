<?php

namespace CannyDain\ShortyCoreModules\SimpleBlog\Installer;

use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Lib\Forms\Models\InputField;

class SimpleBlogInstaller implements ModuleInstallerInterface
{
    public function getName()
    {
        return 'Simple Blog';
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