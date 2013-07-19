<?php

namespace CannyDain\Lib\Execution\Interfaces;

interface ControllerInterface
{
    /**
     * @return bool
     */
    public function __isAdministratorOnly();
}