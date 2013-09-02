<?php

namespace CannyDain\Shorty\Exceptions;

use CannyDain\Lib\Routing\Models\Route;

class ControllerNotFoundException extends RoutingException
{
    public function __construct(Route $route)
    {
        parent::__construct($route, "The specified controller could not be found.");
    }
}