<?php

namespace CannyDain\Shorty\RouteAccessControl;

use CannyDain\Lib\Execution\Interfaces\ControllerFactoryInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\ControllerFactoryConsumer;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\Consumers\UserConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\Shorty\Helpers\UserHelper;

class DefaultRouteAccessControl implements RouteAccessControlInterface, ControllerFactoryConsumer, SessionConsumer, UserConsumer
{
    /**
     * @var ControllerFactoryInterface
     */
    protected $_controllerFactory;

    /**
     * @var UserHelper
     */
    protected $_users;

    /**
     * @var SessionHelper
     */
    protected $_session;

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

    protected function _getDefaultAccess(Route $route)
    {
        $controller = $this->_controllerFactory->getControllerByName($route->getController());
        if ($controller == null || !($controller instanceof ShortyController))
            return $this->_defaultAccess;

        /**
         * @var ShortyController $controller
         */
        switch($controller->getDefaultMinimumAccessLevel())
        {
            case self::ACCESS_LEVEL_PUBLIC:
                return true;
                break;
            case self::ACCESS_LEVEL_MEMBER:
                return $this->_session->getUserID() > 0;
                break;
            case self::ACCESS_LEVEL_ADMIN:
                if ($this->_session->getUserID() == 0)
                    return false;

                return $this->_users->isAdmin($this->_session->getUserID());
                break;
        }

        return false;
    }

    public function canAccessRoute(Route $route)
    {
        $access = $this->_getDefaultAccess($route);
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

    public function consumeControllerFactory(ControllerFactoryInterface $controllerFactory)
    {
        $this->_controllerFactory = $controllerFactory;
    }

    public function consumeSession(SessionHelper $session)
    {
        $this->_session = $session;
    }

    public function consumerUserHelper(UserHelper $helper)
    {
        $this->_users = $helper;
    }
}