<?php

namespace CannyDain\Shorty\RouteAccessControl;

use CannyDain\Lib\Routing\Models\Route;

interface RouteAccessControlInterface
{
    public function canAccessRoute(Route $route);
}