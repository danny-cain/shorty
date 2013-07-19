<?php

namespace CannyDain\ShortyCoreModules\TemplateManager\Installer;

use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Lib\Forms\Models\InputField;

class TemplateManagerInstaller implements ModuleInstallerInterface
{
    public function getName() { return "TemplateManager"; }

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