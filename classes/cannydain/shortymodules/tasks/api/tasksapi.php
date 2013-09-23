<?php

namespace CannyDain\ShortyModules\Tasks\API;

use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Lib\ObjectPermissions\ObjectPermissionsManagerInterface;
use CannyDain\Shorty\Consumers\GUIDConsumer;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Consumers\ObjectPermissionsConsumer;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\Consumers\UserConsumer;
use CannyDain\Shorty\Exceptions\InvalidStateException;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\Shorty\Helpers\UserHelper;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\Tasks\DataLayer\TasksDataLayer;
use CannyDain\ShortyModules\Tasks\Models\ProjectModel;
use CannyDain\ShortyModules\Tasks\Models\TaskModel;
use CannyDain\ShortyModules\Tasks\Providers\TasksPermissionsInfoProvider;
use CannyDain\ShortyModules\Tasks\TasksModule;

class TasksAPI implements ModuleConsumer, ObjectPermissionsConsumer, SessionConsumer, UserConsumer, GUIDConsumer
{
    /**
     * @var TasksDataLayer
     */
    protected $_datasource;

    /**
     * @var ObjectPermissionsManagerInterface
     */
    protected $_permissions;

    /**
     * @var UserHelper
     */
    protected $_users;

    /**
     * @var SessionHelper
     */
    protected $_session;

    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    /**
     * @return ProjectModel[]
     */
    public function getAllProjects()
    {
        $ret = array();
        $projects = $this->_datasource->getAllProjects();
        foreach ($projects as $project)
        {
            if ($project->getOwner() == $this->_session->getUserID())
                $hasPermissions = true;
            else
                $hasPermissions = $this->_permissions->hasAnyOf($this->_userGUID(), $project->getGUID(), array
                (
                    TasksPermissionsInfoProvider::PERMISSION_CREATE_TASKS,
                    TasksPermissionsInfoProvider::PERMISSION_READ,
                    TasksPermissionsInfoProvider::PERMISSION_UPDATE,
                    TasksPermissionsInfoProvider::PERMISSION_MANAGE_PERMISSIONS,
                    TasksPermissionsInfoProvider::PERMISSION_DELETE,
                ));

            if ($hasPermissions)
                $ret[] = $project;
        }

        return $ret;
    }

    /**
     * @param $projectID
     * @return TaskModel[]
     */
    public function getTasksByProjectID($projectID)
    {
        $tasks = $this->_datasource->getTasksByProject($projectID);
        $ret = array();

        foreach ($tasks as $task)
        {
            if ($this->_canAccessTask($task))
                $ret[] = $task;
        }

        return $ret;
    }

    /**
     * @param $searchTerm
     * @return \CannyDain\ShortyModules\Tasks\Models\TaskModel[]
     * @return \CannyDain\ShortyModules\Tasks\Models\TaskModel[]
     */
    public function searchTasks($searchTerm)
    {
        $tasks = $this->_datasource->searchTasks($searchTerm);
        $ret = array();

        foreach ($tasks as $task)
        {
            if ($this->_canAccessTask($task))
                $ret[] = $task;
        }

        return $ret;
    }

    public function loadTask($id)
    {
        $task = $this->_datasource->loadTask($id);
        if ($this->_canAccessTask($task))
            return $task;

        return null;
    }

    protected function _canAccessTask(TaskModel $task)
    {
        $project = $this->loadProject($task->getProjectID());

        if ($project == null)
            return false;

        return true;
    }

    public function loadProject($id)
    {
        $project = $this->_datasource->loadProject($id);

        if ($project->getOwner() == $this->_session->getUserID())
            return $project;

        $hasPermissions = $this->_permissions->hasAnyOf($this->_userGUID(), $project->getGUID(), array
        (
            TasksPermissionsInfoProvider::PERMISSION_CREATE_TASKS,
            TasksPermissionsInfoProvider::PERMISSION_MANAGE_PERMISSIONS,
            TasksPermissionsInfoProvider::PERMISSION_UPDATE,
            TasksPermissionsInfoProvider::PERMISSION_READ,
        ));

        if ($hasPermissions)
            return $project;

        return null;
    }

    public function createProject($name)
    {
        //todo check permissions
        $project = $this->_datasource->createProject();
        $project->setName($name);
        $project->setOwner($this->_session->getUserID());

        $project->save();

        return $project;
    }

    public function createTask($name, $projectID, $shortDesc, $longDesc)
    {
        $project = $this->loadProject($projectID);
        if ($project == null)
            return null;

        if ($project->getOwner() != $this->_session->getUserID() && !$this->_permissions->hasAnyOf($this->_userGUID(), $project->getGUID(), array(TasksPermissionsInfoProvider::PERMISSION_CREATE_TASKS)))
        {
            return null;
        }

        $task = $this->_datasource->createTask();
        $task->setCreatedDate(time());
        $task->setLongDesc($longDesc);
        $task->setProjectID($projectID);
        $task->setShortDesc($shortDesc);
        $task->setTitle($name);

        $task->save();

        return $task;
    }

    protected function _getProjectGUID($id)
    {
        $project = $this->_datasource->loadProject($id);

        return $project->getGUID();
    }

    protected function _getTaskGUID($id)
    {
        $task = $this->_datasource->loadTask($id);

        return $task->getGUID();
    }

    protected function _userGUID()
    {
        return $this->_users->getUserGUID($this->_session->getUserID());
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        /**
         * @var TasksModule $module
         */
        $module = $manager->getModuleByClassname(TasksModule::TASKS_MODULE_NAME);
        if ($module == null)
            throw new InvalidStateException(__CLASS__, "Tasks module not available");

        $this->_datasource = $module->getDatasource();
    }

    public function consumeObjectPermissionsManager(ObjectPermissionsManagerInterface $manager)
    {
        $this->_permissions = $manager;
    }

    public function consumeSession(SessionHelper $session)
    {
        $this->_session = $session;
    }

    public function consumerUserHelper(UserHelper $helper)
    {
        $this->_users = $helper;
    }

    public function consumeGUIDManager(GUIDManagerInterface $guidManager)
    {
        $this->_guids = $guidManager;
    }
}