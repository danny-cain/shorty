<?php

namespace CannyDain\Shorty\RouteAccessControl;

use CannyDain\Lib\Routing\Models\Route;

class DefaultRouteAccessControl implements RouteAccessControlInterface
{
    protected $_defaultAccess = false;

    /**
     * @var Route[]
     */
    protected $_grantedRoutes = array();

    /**
     * @var Route[]
     */
    protected $_deniedRoutes = array();

    /**
     * @param $_defaultAccess
     * @param Route[] $_grantedRoutes
     * @param Route[] $_deniedRoutes
     */
    public function __construct($_defaultAccess, $_grantedRoutes = array(), $_deniedRoutes = array())
    {
        $this->_defaultAccess = $_defaultAccess;
        $this->_grantedRoutes = $_grantedRoutes;
        $this->_deniedRoutes = $_deniedRoutes;
    }

    public function canAccessRoute(Route $route)
    {
        $access = $this->_defaultAccess;
        $strongestGrant = $this->_getStrongestRouteMatchFromArray($route, $this->_grantedRoutes);
        $strongestDeny = $this->_getStrongestRouteMatchFromArray($route, $this->_deniedRoutes);

        $grantStrength = -1;
        $denyStrength = -1;

        if ($strongestGrant != null)
            $grantStrength = $route->getContainStrength($strongestGrant);

        if ($strongestDeny != null)
            $denyStrength = $route->getContainStrength($strongestDeny);

        if ($strongestGrant == null && $strongestDeny == null)
            return $access;

        if ($strongestGrant == null)
            return false;

        if ($strongestDeny == null)
            return true;

        if ($grantStrength > $denyStrength)
            return true;

        return false;
    }

    /**
     * @param Route $route
     * @param Route[] $routes
     * @return \CannyDain\Lib\Routing\Models\Route|null
     */
    protected function _getStrongestRouteMatchFromArray(Route $route, $routes = array())
    {
        $matchingRoute = null;
        $matchStrength = -1;

        foreach ($routes as $possibleMatch)
        {
            $str = $route->getContainStrength($possibleMatch);
            if ($str > $matchStrength)
            {
                $matchStrength = $str;
                $matchingRoute = $possibleMatch;
            }
        }

        return $matchingRoute;
    }
}