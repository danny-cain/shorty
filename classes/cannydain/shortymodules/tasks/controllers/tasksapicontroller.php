<?php

namespace CannyDain\ShortyModules\Tasks\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\JSONView;
use CannyDain\Lib\UI\Views\PlainTextView;
use CannyDain\ShortyModules\Tasks\Models\ProjectModel;
use CannyDain\ShortyModules\Tasks\Models\TaskModel;

class TasksAPIController extends TasksBaseController
{
    const TASKS_API_CONTROLLER = __CLASS__;

    public static function getLatestAPIVersion()
    {
        $path = dirname(dirname(__FILE__)).'/data/';
        $dir = opendir($path);

        $highestVersion = null;
        while ($file = readdir($dir))
        {
            if ($file == '.' || $file == '..')
                continue;

            $fullPath = $path.$file;
            if (!is_file($fullPath))
                continue;

            $parts = explode('.', $file);
            if(strtolower(array_pop($parts)) != 'js')
                continue;

            $parts = explode('-', implode('.', $parts));
            $version = array_pop($parts);
            if (strtolower(implode('-', $parts)) != 'api')
                continue;

            if (strtolower(substr($version, 0, 1)) != 'v')
                continue;

            $version = intval(substr($version, 1));
            if ($highestVersion == null || $version > $highestVersion)
                $highestVersion = $version;
        }

        return $highestVersion;
    }

    public function getAPIJS($version = 1)
    {
        $version = intval($version);
        $jsFile = dirname(dirname(__FILE__)).'/data/api-v'.$version.'.js';
        $data = file_get_contents($jsFile);

        // insert url's
        $data = strtr($data, array
        (
            '#editTaskURI#' => $this->_router->getURI(new Route(__CLASS__, 'editTask', array('#id#'))),
            '#editProjectURI#' => $this->_router->getURI(new Route(__CLASS__, 'editProject', array('#id#'))),
            '#getTaskURI#' => $this->_router->getURI(new Route(__CLASS__, 'getTask', array('#id#'))),
            '#createProjectURI#' => $this->_router->getURI(new Route(__CLASS__, 'createProject', array('#name#'))),
            '#createTaskURI#' => $this->_router->getURI(new Route(__CLASS__, 'createTask', array('#project#', '#title#', '#shortDesc#', '#longDesc#'))),
            '#getProjectURI#' => $this->_router->getURI(new Route(__CLASS__, 'getProject', array('#id#'))),
            '#listProjectsURI#' => $this->_router->getURI(new Route(__CLASS__, 'listProjects')),
            '#listAllTasks#' => $this->_router->getURI(new Route(__CLASS__, 'listAllTasks', array('#project#'))),
            '#searchTasks#' => $this->_router->getURI(new Route(__CLASS__, 'searchTasks', array('#term#'))),
        ));

        return new PlainTextView($data, 'application/javascript');
    }

    public function searchTasks($searchTerm)
    {
        $data = array();

        foreach ($this->_api()->searchTasks($searchTerm) as $task)
        {
            $data[] = $this->_getTaskJSON($task);
        }

        return new JSONView($data);
    }

    public function editTask($id)
    {
        $ret = array('status' => 'Fail', 'message' => 'Must POST to edit');
        $task = $this->_api()->loadTask($id);

        if ($this->_request->isPost())
        {
            $this->_updateTaskFromPost($task);
            $task->save();

            $ret['status'] = 'Ok';
            $ret['message'] = 'Saved';
        }
        $ret['task'] = $task;

        return new JSONView($ret);
    }

    public function editProject($id)
    {
        $ret = array('status' => 'Fail', 'message' => 'Must POST to edit');
        $project = $this->_api()->loadProject($id);

        if ($this->_request->isPost())
        {
            $this->_updateProjectFromPost($project);
            $project->save();

            $ret['status'] = 'Ok';
            $ret['message'] = 'Saved';
        }
        $ret['project'] = $project;

        return new JSONView($ret);
    }

    public function getTask($id)
    {
        return new JSONView($this->_getTaskJSON($this->_api()->loadTask($id)));
    }

    public function createProject()
    {
        $project = $this->_api()->createProject('');
        $this->_updateProjectFromPost($project);

        if ($this->_request->isPost())
            $project->save();

        return new JSONView($this->_getProjectJSON($project));
    }

    public function createTask()
    {
        $title = $this->_request->getParameter('title');
        $project = $this->_request->getParameter('project');

        $task = $this->_api()->createTask($title, $project, '', '');
        $this->_updateTaskFromPost($task);

        if ($this->_request->isPost())
            $task->save();

        return new JSONView($this->_getTaskJSON($task));
    }

    public function getProject($id)
    {
        return new JSONView($this->_getProjectJSON($this->_api()->loadProject($id)));
    }

    public function listProjects()
    {
        $data = array();

        foreach ($this->_api()->getAllProjects() as $project)
            $data[] = $this->_getProjectJSON($project);

        return new JSONView($data);
    }

    public function listAllTasks($project)
    {
        $data = array();

        foreach ($this->_api()->getTasksByProjectID($project) as $task)
            $data[] = $this->_getTaskJSON($task);

        return new JSONView($data);
    }

    protected function _updateTaskFromPost(TaskModel $task)
    {
        $task->setTitle($this->_request->getParameterOrDefault('title', $task->getTitle()));
        $task->setProjectID($this->_request->getParameterOrDefault('project', $task->getProjectID()));
        $task->setShortDesc($this->_request->getParameterOrDefault('shortDesc', $task->getShortDesc()));
        $task->setLongDesc($this->_request->getParameterOrDefault('longDesc', $task->getLongDesc()));
    }

    protected function _updateProjectFromPost(ProjectModel $project)
    {
        $project->setName($this->_request->getParameterOrDefault('name', $project->getName()));
    }

    protected function _getProjectJSON(ProjectModel $project)
    {
        return array
        (
            'id' => $project->getId(),
            'name' => $project->getName(),
            'guid' => $project->getGUID()
        );
    }

    protected function _getTaskJSON(TaskModel $task)
    {
        return array
        (
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'estimate' => $task->getEstimate(),
            'priority' => $task->getPriority(),
            'cost' => $task->getCostInPence(),
            'created' => date('Y-m-d H:i:s', $task->getCreatedDate()),
            'completed' => date('Y-m-d H:i:s', $task->getCompletedDate()),
            'status' => $task->getStatus(),
            'guid' => $task->getGUID(),
            'longDesc' => $task->getLongDesc(),
            'shortDesc' => $task->getShortDesc(),
            'project' => $task->getProjectID(),
        );
    }
}