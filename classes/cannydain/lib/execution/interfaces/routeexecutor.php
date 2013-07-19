<?php

namespace CannyDain\Lib\Execution\Interfaces;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\ViewInterface;

interface RouteExecutor
{
    /**
     * @param Route $route
     * @return ViewInterface
     */
    public function executeRouteAndReturnView(Route $route);
}