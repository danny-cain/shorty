<?php

namespace CannyDain\ShortyCoreModules\URIManager\Installer;

use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Lib\Forms\Models\InputField;

class URIManagerInstaller implements ModuleInstallerInterface
{
    public function getName() { return "URIManager"; }

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