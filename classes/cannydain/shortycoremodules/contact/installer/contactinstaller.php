<?php

namespace CannyDain\ShortyCoreModules\Contact\Installer;

use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Lib\Forms\Models\InputField;

class ContactInstaller implements ModuleInstallerInterface
{
    public function getName() { return "Contact"; }

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