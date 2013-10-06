<?php

namespace CannyDain\Lib\Execution\Interfaces;

interface ControllerFactoryInterface
{
    /**
     * @param $name
     * @return ControllerInterface
     */
    public function getControllerByName($name);
}