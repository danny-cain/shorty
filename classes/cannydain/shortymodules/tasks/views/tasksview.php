<?php

namespace CannyDain\ShortyModules\Tasks\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Views\ShortyView;

class TasksView extends ShortyView
{
    /**
     * @var Route
     */
    protected $_jsRoute;

    public function display()
    {
        $uri = $this->_router->getURI($this->_jsRoute);
        echo <<<HTML
<script type="text/javascript" src="{$uri}"></script>
<script type="text/javascript">
    $(document).ready(function()
    {
        var containers = {};

        containers.container = $('#tasksContainer');
        containers.projectListPane = $('.projectListingPane', containers.container);
        containers.taskListPane = $('.taskListingPane', containers.container);
        containers.taskEditPane = $('.editTask', containers.container);
        containers.projectEditPane = $('.editProject', containers.container);

        function updateProjectFromEditPane(project)
        {
            project.name = $('[name="name"]', containers.projectEditPane).val();
        }

        function updateTaskFromEditPane(task)
        {
            task.title = $('[name="title"]', containers.taskEditPane).val();
            task.shortDesc = $('[name="shortDesc"]', containers.taskEditPane).val();
            task.longDesc = $('[name="longDesc"]', containers.taskEditPane).val();
            task.project = $('[name="project"]', containers.taskEditPane).val();
            task.id = containers.taskEditPane.attr('data-id');
        }

        function updateProjectEditPane(project)
        {
            containers.projectEditPane.attr('data-id', project.id);

            $('[name="name"]', containers.projectEditPane).val(project.name);
        }

        function updateTaskEditPane(task)
        {
            containers.taskEditPane.attr('data-id', task.id);

            $('[name="title"]', containers.taskEditPane).val(task.title);
            $('[name="shortDesc"]', containers.taskEditPane).val(task.shortDesc);
            $('[name="longDesc"]', containers.taskEditPane).val(task.longDesc);
            $('[name="project"]', containers.taskEditPane).val(task.project);
        }

        $('.cancelButton', containers.projectEditPane).bind('click.PM', function()
        {
            containers.projectEditPane.hide();
        });

        $('.saveButton', containers.projectEditPane).bind('click.PM', function()
        {
            containers.projectEditPane.hide();
            var id = parseInt(containers.projectEditPane.attr('data-id'));
            var project = new shorty.models.Project();

            project.id = id;
            updateProjectFromEditPane(project);

            pmController.saveProject(project, function()
            {
                pmController.getProjects();
            });
        });

        $('.cancelButton', containers.taskEditPane).bind('click.PM', function()
        {
            containers.taskEditPane.hide();
        });

        $('.saveButton', containers.taskEditPane).bind('click.PM', function()
        {
            containers.taskEditPane.hide();

            var task = new shorty.models.Task();

            updateTaskFromEditPane(task);

            pmController.saveTask(task, function()
            {
                refreshTasksList();
            });
        });


        var requestCount = 0;
        var pmController = new shorty.controllers.ProjectManagement(
        {
            listProjectsCallback : function(projects)
            {
                containers.projectListPane.empty();
                containers.projectListPane.append('<div class="createProject">Create Project</div>');
                for (var i = 0; i < projects.length; i ++)
                {
                    containers.projectListPane.append('<div data-id="' + projects[i].id + '" class="project"><span class="projectName">' + projects[i].name + '</span><span class="editProjectButton">Edit</span></div>');
                }

                $('.projectName', containers.projectListPane).unbind('click.PM').bind('click.PM', function()
                {
                    if (tasksListTimer != null)
                        clearTimeout(tasksListTimer);

                    pmController.getTasksByProject($(this).parent().attr('data-id'));
                    containers.taskEditPane.hide();
                    containers.projectEditPane.hide();

                    setTimeout(refreshTasksList, 5000);
                });

                $('.editProjectButton', containers.projectListPane).unbind('click.PM').bind('click.PM', function()
                {
                    pmController.getProjectByID($(this).parent().attr('data-id'), function(project)
                    {
                        containers.taskEditPane.hide();

                        updateProjectEditPane(project);

                        containers.projectEditPane.show();
                    });
                });

                $('.createProject', containers.projectListPane).unbind('click.PM').bind('click.PM', function()
                {
                    containers.taskEditPane.hide();
                    updateProjectEditPane(new shorty.models.Project());
                    containers.projectEditPane.show();
                });
            },
            listTasksCallback : function(tasks, callerData)
            {
                containers.taskListPane.empty();

                containers.taskListPane.attr('data-list', JSON.stringify(callerData));
                if (callerData.method == "getTasksByProject")
                {
                    containers.taskListPane.attr('project-id', callerData.arguments[0]);
                    containers.taskListPane.append('<div class="createTask" data-project-id="' + callerData.arguments[0] + '">Create Task</div>');
                }
                else
                    containers.taskListPane.attr('project-id', '0');

                for (var i = 0; i < tasks.length; i ++)
                {
                    containers.taskListPane.append('<div data-id="' + tasks[i].id + '" class="task editTaskButton">' + tasks[i].title + ' (' + tasks[i].created + ')</div>');
                }

                $('.createTask', containers.taskListPane).unbind('click.PM').bind('click.PM', function()
                {
                    containers.projectEditPane.hide();
                    var task = new shorty.models.Task();
                    task.project = parseInt($(this).attr('data-project-id'));

                    updateTaskEditPane(task);

                    containers.taskEditPane.show();
                });

                $('.editTaskButton', containers.taskListPane).unbind('click.PM').bind('click.PM', function()
                {
                    pmController.getTaskByID($(this).attr('data-id'), function(task)
                    {
                        containers.projectEditPane.hide();

                        updateTaskEditPane(task);

                        containers.taskEditPane.show();
                    });
                });
            },
            startRequestCallback : function(uri)
            {
                requestCount ++;
                console.log("++" + uri);
                if (requestCount == 1)
                    $('.loadingIcon', containers.container).show();
            },
            endRequestCallback : function(uri)
            {
                requestCount --;
                console.log("--" + uri);
                if (requestCount == 0)
                    $('.loadingIcon', containers.container).hide();
            }
        });

        pmController.getProjects();
        // setup ui logic here

        var tasksListTimer = null;

        function refreshTasksList()
        {
            if (tasksListTimer != null)
                clearTimeout(tasksListTimer);

            var callerStr = containers.taskListPane.attr('data-list');

            if (callerStr == undefined)
                return;

            if (callerStr == '')
                return;

            var caller = JSON.parse(callerStr);
            var func = pmController[caller.method];

            if (func != undefined)
            {
                func.apply(pmController, caller.arguments);
            }

            tasksListTimer = setTimeout(refreshTasksList, 5000);
        }

        window.pmController = pmController;
    });
</script>

<div id="tasksContainer">
    <div class="loadingIcon">Loading...</div>
    <div class="pmLeftPane projectListingPane">

    </div>

    <div class="pmRightPane">
        <div class="taskListingPane">

        </div>

        <div class="editPane">
            <div class="editProject" style="display: none;">
                <div class="row">
                    <div class="caption">
                        Name:
                    </div>
                    <div class="field">
                        <input type="text" name="name" />
                    </div>
                </div>

                <div class="buttonRow">
                    <button class="saveButton">Save</button>
                    <button class="cancelButton">Cancel</button>
                </div>
            </div>

            <div class="editTask" style="display: none;">
                <input type="hidden" name="project" />
                <div class="row">
                    <div class="caption">
                        Title:
                    </div>
                    <div class="field">
                        <input type="text" name="title" />
                    </div>
                </div>

                <div class="row">
                    <div class="caption">
                        Short Description:
                    </div>
                    <div class="field">
                        <input type="text" name="shortDesc" />
                    </div>
                </div>

                <div class="row">
                    <div class="caption">
                        Long Description:
                    </div>
                    <div class="field">
                        <textarea name="longDesc"></textarea>
                    </div>
                </div>

                <div class="buttonRow">
                    <button class="saveButton">Save</button>
                    <button class="cancelButton">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
HTML;

    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $jsRoute
     */
    public function setJsRoute($jsRoute)
    {
        $this->_jsRoute = $jsRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getJsRoute()
    {
        return $this->_jsRoute;
    }
}