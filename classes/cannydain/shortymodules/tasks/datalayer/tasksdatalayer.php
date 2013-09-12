<?php

namespace CannyDain\ShortyModules\Tasks\DataLayer;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Shorty\DataAccess\ShortyDatasource;
use CannyDain\ShortyModules\Tasks\Models\ProjectModel;
use CannyDain\ShortyModules\Tasks\Models\TaskModel;

class TasksDataLayer extends ShortyDatasource
{
    /**
     * @return ProjectModel[]
     */
    public function getAllProjects()
    {
        return $this->_datamapper->getAllObjects(ProjectModel::PROJECT_OBJECT_TYPE);
    }

    /**
     * @param $searchTerm
     * @return TaskModel[]
     */
    public function searchTasks($searchTerm)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(TaskModel::TASK_OBJECT_TYPE, array
        (
            'shortDesc LIKE :term OR longDesc LIKE :term OR title LIKE :term'
        ), array
        (
            ':term' => '%'.$searchTerm.'%'
        ));
    }

    /**
     * @param $projectID
     * @return TaskModel[]
     */
    public function getTasksByProject($projectID)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(TaskModel::TASK_OBJECT_TYPE, array
        (
            'project = :project'
        ), array
        (
            'project' => $projectID
        ));
    }

    /**
     * @param $id
     * @return TaskModel
     */
    public function loadTask($id)
    {
        return $this->_datamapper->loadObject(TaskModel::TASK_OBJECT_TYPE, array('id' => $id));
    }

    /**
     * @param $id
     * @return ProjectModel
     */
    public function loadProject($id)
    {
        return $this->_datamapper->loadObject(ProjectModel::PROJECT_OBJECT_TYPE, array('id' => $id));
    }

    public function createTask()
    {
        $task = new TaskModel();
        $this->_dependencies->applyDependencies($task);

        return $task;
    }

    public function createProject()
    {
        $project = new ProjectModel();
        $this->_dependencies->applyDependencies($project);

        return $project;
    }

    public function registerObjects()
    {
        $file = dirname(__FILE__).'/datadictionary.json';
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile($file, $this->_datamapper);
    }
}