<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\Execution\Interfaces\ControllerFactoryInterface;

interface ControllerFactoryConsumer
{
    public function consumeControllerFactory(ControllerFactoryInterface $controllerFactory);
}