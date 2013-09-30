<?php

namespace CannyDain\ShortyModules\Tasks\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\ShortyModules\Tasks\Views\TasksView;

class TasksController extends TasksBaseController
{
    const CONTROLLER_NAME = __CLASS__;

    public function Index()
    {
        return $this->View(TasksAPIController::getLatestAPIVersion());
    }

    public function View($version)
    {
        $view = new TasksView();

        $view->setJsRoute(new Route(TasksAPIController::TASKS_API_CONTROLLER, 'getAPIJS', array($version)));

        return $view;
    }
}