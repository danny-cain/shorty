<?php

namespace CannyDain\Lib\ObjectPermissions\Interfaces;

interface PermissionsInfoProvider
{
    /**
     * returns an array of <char> => <string> where char is the permission signifier and <string> is the name of the permission
     * @return array
     */
    public function getAvailablePermissions();

    /**
     * @return array
     */
    public function getDefaultPermissions();
}