<?php

namespace CannyDain\ShortyCoreModules\UserModule\Controllers;

use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Shorty\Controllers\ShortyController;

class UserAPIController extends ShortyController
{
    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return false;
    }
}