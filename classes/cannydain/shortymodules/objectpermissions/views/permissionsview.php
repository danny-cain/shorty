<?php

namespace CannyDain\ShortyModules\ObjectPermissions\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Views\ShortyView;
use CannyDain\ShortyModules\ObjectPermissions\Models\PermissionModel;

class PermissionsView extends ShortyView
{
    /**
     * @var Route
     */
    protected $_saveRoute;

    /**
     * @var PermissionModel[]
     */
    protected $_permissions = array();

    public function display()
    {
        // TODO: Implement display() method.
    }

    public function setPermissions($permissions)
    {
        $this->_permissions = $permissions;
    }

    public function getPermissions()
    {
        return $this->_permissions;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $saveRoute
     */
    public function setSaveRoute($saveRoute)
    {
        $this->_saveRoute = $saveRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getSaveRoute()
    {
        return $this->_saveRoute;
    }
}