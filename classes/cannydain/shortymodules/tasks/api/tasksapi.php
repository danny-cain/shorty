<?php

namespace CannyDain\ShortyModules\Tasks\API;

use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Exceptions\InvalidStateException;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\Tasks\DataLayer\TasksDataLayer;
use CannyDain\ShortyModules\Tasks\Models\ProjectModel;
use CannyDain\ShortyModules\Tasks\Models\TaskModel;
use CannyDain\ShortyModules\Tasks\TasksModule;

class TasksAPI implements ModuleConsumer
{
    /**
     * @var TasksDataLayer
     */
    protected $_datasource;

    /**
     * @return ProjectModel[]
     */
    public function getAllProjects()
    {
        return $this->_datasource->getAllProjects();
    }

    /**
     * @param $projectID
     * @return TaskModel[]
     */
    public function getTasksByProjectID($projectID)
    {
        return $this->_datasource->getTasksByProject($projectID);
    }

    /**
     * @param $searchTerm
     * @return \CannyDain\ShortyModules\Tasks\Models\TaskModel[]
     * @return \CannyDain\ShortyModules\Tasks\Models\TaskModel[]
     */
    public function searchTasks($searchTerm)
    {
        return $this->_datasource->searchTasks($searchTerm);
    }

    public function loadTask($id)
    {
        return $this->_datasource->loadTask($id);
    }

    public function loadProject($id)
    {
        return $this->_datasource->loadProject($id);
    }

    public function createProject($name)
    {
        $project = $this->_datasource->createProject();
        $project->setName($name);
        $project->save();

        return $project;
    }

    public function createTask($name, $projectID, $shortDesc, $longDesc)
    {
        $task = $this->_datasource->createTask();
        $task->setCreatedDate(time());
        $task->setLongDesc($longDesc);
        $task->setProjectID($projectID);
        $task->setShortDesc($shortDesc);
        $task->setTitle($name);

        $task->save();

        return $task;
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
}