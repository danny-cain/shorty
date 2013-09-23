<?php

namespace CannyDain\ShortyModules\Tasks\Providers;

use CannyDain\Lib\ObjectPermissions\Interfaces\PermissionsInfoProvider;

class TasksPermissionsInfoProvider implements PermissionsInfoProvider
{
    const PERMISSION_READ = 'r';
    const PERMISSION_UPDATE = 'w';
    const PERMISSION_DELETE = 'd';
    const PERMISSION_MANAGE_PERMISSIONS = 'm';
    const PERMISSION_CREATE_TASKS = 'c';

    /**
     * returns an array of <char> => <string> where char is the permission signifier and <string> is the name of the permission
     * @return array
     */
    public function getAvailablePermissions()
    {
        return array
        (
            self::PERMISSION_READ => 'Read',
            self::PERMISSION_UPDATE => 'Update',
            self::PERMISSION_DELETE => 'Delete',
            self::PERMISSION_MANAGE_PERMISSIONS => 'Manage Permissions',
            self::PERMISSION_CREATE_TASKS => 'Create Tasks',
        );
    }

    /**
     * @return array
     */
    public function getDefaultPermissions()
    {
        return array();
    }
}