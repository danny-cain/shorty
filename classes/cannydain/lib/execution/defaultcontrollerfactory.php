<?php

namespace CannyDain\Lib\Execution;

use CannyDain\Lib\Execution\Exceptions\ControllerNotFoundException;
use CannyDain\Lib\Execution\Interfaces\ControllerFactoryInterface;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;

class DefaultControllerFactory implements ControllerFactoryInterface
{
    /**
     * @param $name
     * @throws Exceptions\ControllerNotFoundException
     * @return ControllerInterface
     */
    public function getControllerByName($name)
    {
        if (!class_exists($name))
            throw new ControllerNotFoundException($name." could not be found");

        $controller = new $name();
        if (!($controller instanceof ControllerInterface))
            throw new ControllerNotFoundException($name." is not a controller");

        return $controller;
    }
}