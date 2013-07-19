<?php

namespace CannyDain\Shorty\Modules;

use CannyDain\Shorty\Modules\Interfaces\ModuleInterface;

abstract class BaseModule implements ModuleInterface
{
    /**
     * @return string
     */
    public function getAdminControllerName()
    {
        return '';
    }
}