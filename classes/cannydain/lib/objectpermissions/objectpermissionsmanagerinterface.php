<?php

namespace CannyDain\Lib\ObjectPermissions;

use CannyDain\Lib\ObjectPermissions\Interfaces\PermissionsInfoProvider;

interface ObjectPermissionsManagerInterface
{
    public function registerProvider(PermissionsInfoProvider $provider);

    public function grant($consumerGUID, $objectGUID, $permissions);
    public function revoke($consumerGUID, $objectGUID, $permissions);
    public function hasAnyOf($userGUID, $objectGUID, $permissions = array());
    public function hasAllOf($userGUID, $objectGUID, $permissions = array());

    /**
     * @param $objectGUID
     * @param bool $canEdit
     * @return \CannyDain\Lib\UI\Views\ViewInterface
     */
    public function getPermissionsViewForObject($objectGUID, $canEdit = false);
}