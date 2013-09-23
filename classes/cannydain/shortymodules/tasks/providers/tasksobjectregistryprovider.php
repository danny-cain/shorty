<?php

namespace CannyDain\ShortyModules\Tasks\Providers;

use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Lib\GUIDS\Models\ObjectInfoModel;
use CannyDain\Lib\GUIDS\ObjectRegistryProvider;
use CannyDain\Shorty\Consumers\GUIDConsumer;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\Tasks\DataLayer\TasksDataLayer;
use CannyDain\ShortyModules\Tasks\Models\ProjectModel;
use CannyDain\ShortyModules\Tasks\Models\TaskModel;
use CannyDain\ShortyModules\Tasks\TasksModule;
use CannyDain\ShortyModules\Users\Datasource\UsersDatasource;
use CannyDain\ShortyModules\Users\Models\User;
use CannyDain\ShortyModules\Users\UsersModule;

class TasksObjectRegistryProvider implements ObjectRegistryProvider, ModuleConsumer, GUIDConsumer
{
    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    /**
     * @var TasksDataLayer
     */
    protected $_datasource;

    /**
     * @param string $searchTerm
     * @param string $typeLimit
     * @param int $limit
     * @return ObjectInfoModel[]
     */
    public function searchObjects($searchTerm, $typeLimit = null, $limit = 0)
    {
        $tasks = array();
        $projects = array();

        //todo add task and project searching
        return array_merge($tasks, $projects);
    }

    /**
     * @param $guid
     * @return string
     */
    public function getNameOfObject($guid)
    {
        $id = $this->_guids->getID($guid);
        $type = $this->_guids->getType($guid);

        switch($type)
        {
            case TaskModel::TASK_OBJECT_TYPE:
                return $this->_datasource->loadTask($id)->getTitle();
            case ProjectModel::PROJECT_OBJECT_TYPE:
                return $this->_datasource->loadProject($id)->getName();
        }

        return '';
    }

    /**
     * @return array
     */
    public function getKnownTypes()
    {
        return array
        (
            TaskModel::TASK_OBJECT_TYPE,
            ProjectModel::PROJECT_OBJECT_TYPE
        );
    }

    public function consumeGUIDManager(GUIDManagerInterface $guidManager)
    {
        $this->_guids = $guidManager;
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        /**
         * @var TasksModule $module
         */
        $module = $manager->getModuleByClassname(TasksModule::TASKS_MODULE_NAME);
        if ($module == null || !($module instanceof TasksModule))
            throw new \Exception("Unable to locate tasks module");

        $this->_datasource = $module->getDatasource();
    }
}