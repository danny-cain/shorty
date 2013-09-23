<?php

namespace CannyDain\ShortyModules\Sandbox\Controllers;

use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Lib\ObjectPermissions\ObjectPermissionsManagerInterface;
use CannyDain\Shorty\Consumers\GUIDConsumer;
use CannyDain\Shorty\Consumers\ObjectPermissionsConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\ShortyModules\Tasks\Models\TaskModel;

class SandboxController extends ShortyController implements GUIDConsumer, ObjectPermissionsConsumer
{
    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    /**
     * @var ObjectPermissionsManagerInterface
     */
    protected $_permissions;

    public function Index()
    {
        $objectGUID = $this->_guids->getGUID(TaskModel::TASK_OBJECT_TYPE, 1);
        return $this->_permissions->getPermissionsViewForObject($objectGUID, true);
    }

    public function consumeGUIDManager(GUIDManagerInterface $guidManager)
    {
        $this->_guids = $guidManager;
    }

    public function consumeObjectPermissionsManager(ObjectPermissionsManagerInterface $manager)
    {
        $this->_permissions = $manager;
    }
}