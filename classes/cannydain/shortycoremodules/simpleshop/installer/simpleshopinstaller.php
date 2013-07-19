<?php

namespace CannyDain\ShortyCoreModules\SimpleShop\Installer;

use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Lib\Forms\Models\InputField;

class SimpleShopInstaller implements ModuleInstallerInterface
{
    public function getName() { return "SimpleShop"; }

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