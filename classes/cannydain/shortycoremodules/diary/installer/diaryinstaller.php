<?php

namespace CannyDain\ShortyCoreModules\Diary\Installer;

use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Lib\Forms\Models\InputField;

class DiaryInstaller implements ModuleInstallerInterface
{
    public function getName() { return "Diary"; }

    /**
     * @return InputField[]
     */
    public function getFields() { return array(); }

    /**
     * @param InputField[] $fields
     * @return mixed
     */
    public function install($fields) {}
}