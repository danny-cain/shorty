<?php

namespace CannyDain\ShortyModules\Tasks\Controllers;

use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Shorty\RouteAccessControl\RouteAccessControlInterface;
use CannyDain\ShortyModules\Tasks\API\TasksAPI;

abstract class TasksBaseController extends ShortyController
{
    public function getDefaultMinimumAccessLevel()
    {
        return RouteAccessControlInterface::ACCESS_LEVEL_MEMBER;
    }

    protected function _api()
    {
        static $api = null;

        if ($api == null)
        {
            $api = new TasksAPI();
            $this->_dependencies->applyDependencies($api);
        }

        return $api;
    }
}