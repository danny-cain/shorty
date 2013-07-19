<?php

namespace CannyDain\Shorty\Modules\Interfaces;

use CannyDain\Lib\Forms\Models\InputField;

interface ModuleInstallerInterface
{
    public function getName();

    /**
     * @return InputField[]
     */
    public function getFields();

    /**
     * @param InputField[] $fields
     * @return mixed
     */
    public function install($fields);
}