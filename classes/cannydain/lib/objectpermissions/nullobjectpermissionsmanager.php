<?php

namespace CannyDain\Lib\ObjectPermissions;

use CannyDain\Lib\ObjectPermissions\Interfaces\PermissionsInfoProvider;
use CannyDain\Lib\UI\Views\NullHTMLView;

class NullObjectPermissionsManager implements ObjectPermissionsManagerInterface
{
    public function registerProvider(PermissionsInfoProvider $provider)
    {

    }

    public function grant($consumerGUID, $objectGUID)
    {

    }

    public function revoke($consumerGUID, $objectGUID)
    {

    }

    public function hasAnyOf($userGUID, $objectGUID, $permissions = array())
    {
        return false;
    }

    public function hasAllOf($userGUID, $objectGUID, $permissions = array())
    {
        return false;
    }

    public function getPermissionsViewForObject($objectGUID, $canGrant = false, $canRevoke = false)
    {
        return new NullHTMLView();
    }
}