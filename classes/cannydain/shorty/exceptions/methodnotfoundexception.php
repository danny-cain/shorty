<?php

namespace CannyDain\Shorty\Exceptions;

use CannyDain\Lib\Routing\Models\Route;

class MethodNotFoundException extends RoutingException
{
    public function __construct(Route $route)
    {
        parent::__construct($route, "The specified method could not be found.");
    }
}