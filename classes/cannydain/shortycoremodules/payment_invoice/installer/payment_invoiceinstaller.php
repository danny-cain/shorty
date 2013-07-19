<?php

namespace CannyDain\ShortyCoreModules\payment_invoice\Installer;

use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Lib\Forms\Models\InputField;

class payment_invoiceInstaller implements ModuleInstallerInterface
{
    public function getName() { return "payment_invoice"; }

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