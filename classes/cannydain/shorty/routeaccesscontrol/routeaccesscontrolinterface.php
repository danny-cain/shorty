<?php

namespace CannyDain\Shorty\RouteAccessControl;

use CannyDain\Lib\Routing\Models\Route;

interface RouteAccessControlInterface
{
    const ACCESS_LEVEL_PUBLIC = 0;
    const ACCESS_LEVEL_MEMBER = 1;
    const ACCESS_LEVEL_ADMIN = 2;

    public function canAccessRoute(Route $route);
}