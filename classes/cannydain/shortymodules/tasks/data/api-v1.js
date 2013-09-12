function EditProjectView()
{
	this.container = $();
	this.initialise = function()
	{
		this.container = $('<div class="editProject"></div>');
		this.container.append(this._getEditTextRow('Name', 'name'));
		this.container.append('<div class="buttons"><button class="save">Save</button><button class="cancel">Cancel</button></div>');
		this.container.hide();
	};

	this._getEditTextRow = function(caption, fieldName)
	{
		return '<div class="row"><div class="caption">' + caption + '</div> <div class="field"><input type="text" name="' + fieldName + '" /></div></div>';
	};

	this.getJQObject = function()
	{
		return this.container;
	};

	this.edit = function(projectInfo, saveCallback, cancelCallback)
	{
		var self = this;
		var nameField = $('[name="name"]', this.container);

		$('.save', this.container).unbind('click.editProject').bind('click.editProject', function()
		{
			projectInfo.name = nameField.val();
			saveCallback(projectInfo);
		});

		$('.cancel', this.container).unbind('click.editProject').bind('click.editProject', function()
		{
			cancelCallback();
		});

		nameField.val(projectInfo.name);

		this.getJQObject().show();
	};

	this.hide = function()
	{
		this.getJQObject().hide();
	};
}

function EditTaskView()
{
	this.container = $();

	this.initialise = function()
	{
		this.container = $('<div class="editTask"></div>');
		this.container.append(this._getEditTextRow('Title', 'title'));
		this.container.append(this._getEditTextRow('Short Description', 'shortDesc'));
		this.container.append(this._getEditTextRow('Long Description', 'longDesc'));
		this.container.append('<div class="buttons"><button class="save">Save</button><button class="cancel">Cancel</button></div>');
		this.container.hide();
	};

	this._getEditTextRow = function(caption, fieldName)
	{
		return '<div class="row"><div class="caption">' + caption + '</div> <div class="field"><input type="text" name="' + fieldName + '" /></div></div>';
	};

	this.getJQObject = function()
	{
		return this.container;
	};

	this.edit = function(taskInfo, saveCallback, cancelCallback)
	{
		var self = this;
		var titleField = $('[name="title"]', this.container);
		var shortDescription = $('[name="shortDesc"]', this.container);
		var longDescription = $('[name="longDesc"]', this.container);

		$('.save', this.container).unbind('click.editTask').bind('click.editTask', function()
		{
			taskInfo.title = titleField.val();
			taskInfo.shortDesc = shortDescription.val();
			taskInfo.longDesc = longDescription.val();

			saveCallback(taskInfo);
		});

		$('.cancel', this.container).unbind('click.editTask').bind('click.editTask', function()
		{
			cancelCallback();
		});

		titleField.val(taskInfo.title);
		shortDescription.val(taskInfo.shortDesc);
		longDescription.val(taskInfo.longDesc);

		this.getJQObject().show();
	};

	this.hide = function()
	{
		this.getJQObject().hide();
	};
}

function ListProjectsView()
{
	this.container = $();

	this.initialise = function()
	{
		this.container = $('<div class="listProjects"></div>');
		this.container.hide();
	};

	this.getJQObject = function()
	{
		return this.container;
	};

	this.display = function(projects, selectProjectCallback, editProjectCallback, createProjectCallback)
	{
		if (arguments.length == 0)
		{
			this.container.show();
			return;
		}

		if (selectProjectCallback == undefined)
			selectProjectCallback = function() {};
		if (editProjectCallback == undefined)
			editProjectCallback = function() {};

		var self = this;
		this.container.empty();

		if (createProjectCallback != undefined)
			this.container.append('<div class="createProject">[create project]</div>');

		for (var i = 0; i < projects.length; i ++)
		{
			var row = $('<div class="project" data-id="' + projects[i].id + '" data-guid="' + projects[i].guid + '"></div>');
			var view = $('<span class="projectName viewProject">' + projects[i].name + '</span>');
			var edit = $('<span class="editProject">Edit</span>');

			view.bind('click.projectList', function()
			{
				selectProjectCallback($(this).parent().attr('data-id'));
			});

			edit.bind('click.projectList', function()
			{
				editProjectCallback($(this).parent().attr('data-id'));
			});

			row.append(view);
			row.append(edit);

			this.container.append(row);
		}

		$('.createProject', this.container).unbind('click.projectList').bind('click.projectList', function()
		{
			createProjectCallback();
		});

		this.container.show();
	};

	this.hide = function()
	{
		this.getJQObject().hide();
	};
}

function ListTasksView()
{
	this.container = $();

	this.initialise = function()
	{
		this.container = $('<div class="listTasks"></div>');
		this.container.hide();
	};

	this.getJQObject = function()
	{
		return this.container;
	};

	this.display = function(tasks, editTaskCallback, gotoParentCallback, createTaskCallback)
	{
		if (arguments.length === 0)
		{
			this.container.show();
			return;
		}

		var self = this;

		if (editTaskCallback == undefined)
			editTaskCallback = function() {};

		this.container.empty();

		if (gotoParentCallback != undefined && gotoParentCallback != null)
		{
			var parent = $('<div class="parent">[up]</div>');
			parent.bind('click.taskList', function()
			{
				gotoParentCallback();
			});
			this.container.append(parent);
		}

		if (createTaskCallback != undefined)
			this.container.append($('<div class="createTask">[create task]</div>'));

		for (var i = 0; i < tasks.length; i ++)
		{
			var row = $('<div class="task" data-id="' + tasks[i].id + '" data-guid="' + tasks[i].guid + '"></div>');
			var edit = $('<span class="editTaskButton taskName">' + tasks[i].title + ' (' + tasks[i].created + ')</span>');

			edit.bind('click.taskList', function()
			{
				editTaskCallback($(this).parent().attr('data-id'));
			});

			row.append(edit);

			this.container.append(row);
		}

		$('.createTask', this.container).unbind('click.taskList').bind('click.taskList', function()
		{
			createTaskCallback();
		});

		this.container.show();
	};

	this.hide = function()
	{
		this.getJQObject().hide();
	};
}

function TasksAPIClient(jqObject)
{
	this.config =
	{
		editTaskURI : "#editTaskURI#",
		editProjectURI : "#editProjectURI#",
		getTaskURI : "#getTaskURI#",
		createProjectURI : "#createProjectURI#",
		createTaskURI : "#createTaskURI#",
		getProjectURI : "#getProjectURI#",
		listProjectsURI : "#listProjectsURI#",
		listAllTasks : "#listAllTasks#"
	};

	this.jqAjax = null;

	this.abortLoading = function()
	{
		if (this.jqAjax == null)
			return;

		this.jqAjax.abort();
		this.finishedLoading();
	};

	this.isLoading = function(jqAjax)
	{
		this.abortLoading();
		$('.loadingIcon', this.container).show();
		this.jqAjax = jqAjax;
	};

	this.finishedLoading = function()
	{
		this.jqAjax = null;
		$('.loadingIcon', this.container).hide();
	};

	this.currentProjectID = null;
	this.container = jqObject;
	this.panes =
	{
		editTaskView : new EditTaskView(),
		editProjectView : new EditProjectView(),
		listProjectsView : new ListProjectsView(),
		listTasksView : new ListTasksView(),
		hideAll : function()
		{
			this.editTaskView.hide();
			this.editProjectView.hide();
			//this.listProjectsView.hide();
			//this.listTasksView.hide();
		}
	};

	this.initialise = function ()
	{
		this.panes.editTaskView.initialise();
		this.panes.editProjectView.initialise();
		this.panes.listProjectsView.initialise();
		this.panes.listTasksView.initialise();

		this.container.append('<div class="loadingIcon" style="display: none;">Loading</div>');
		this.container.append('<div class="pmLeftPane projectListingPane"></div>');
		this.container.append('<div class="pmRightPane"><div class="taskListingPane"></div><div class="editPane"></div></div>');

		$('.projectListingPane', this.container).append(this.panes.listProjectsView.getJQObject());
		$('.taskListingPane', this.container).append(this.panes.listTasksView.getJQObject());
		$('.editPane', this.container).append(this.panes.editTaskView.getJQObject());
		$('.editPane', this.container).append(this.panes.editProjectView.getJQObject());
	};

	this.createTask = function(projectID)
	{
		var self = this;

		if (projectID == undefined)
			projectID = self.currentProjectID;

		self.panes.hideAll();
		self.panes.editTaskView.edit(
		{
			id : null,
			title : '',
			shortDesc : '',
			longDesc : '',
			project : projectID
		}, function(task)
		{
			self.isLoading($.post(self.config.createTaskURI, task, function()
			{
				self.finishedLoading();
				self.showTasks(task.project);
			}));
		}, function()
		{
			self.showTasks(self.currentProjectID);
		});
	};

	this.editTask = function(taskID)
	{
		var self = this;
		var url = this.config.getTaskURI.replace("#id#", taskID);
		self.isLoading($.get(url, function(data)
		{
			self.finishedLoading();
			self.panes.hideAll();
			self.panes.editTaskView.edit(data, function(task)
			{
				var uri = self.config.editTaskURI.replace('#id#', task.id);
				self.isLoading($.post(uri, task, function(data)
				{
					self.showTasks(task.project);
				}));
			}, function()
			{
				self.showTasks(data.project);
			});
		}));
	};

	this.createProject = function()
	{
		var self = this;

		this.currentProjectID = null;
		self.panes.hideAll();
		self.panes.editProjectView.edit(
		{
			id : null,
			name : ''
		}, function(project)
		{
			self.isLoading($.post(self.config.createProjectURI, project, function(project)
			{
				self.finishedLoading();
				self.showProjects();
			}));
		}, function()
		{
			self.showProjects();
		});
	};

	this.editProject = function(projectID)
	{
		var self = this;
		var uri = this.config.getProjectURI.replace("#id#", projectID);
		this.currentProjectID = projectID;

		self.isLoading($.get(uri, function(data)
		{
			self.finishedLoading();
			self.panes.hideAll();
			self.panes.editProjectView.edit(data, function(project)
			{
				var uri = self.config.editProjectURI.replace('#id#', project.id);
				self.isLoading($.post(uri, project, function()
				{
					self.finishedLoading();
					self.showProjects();
				}));
			}, function()
			{
				self.showProjects();
			});
		}));
	};

	this.showTasks = function(projectID)
	{
		var self = this;
		var uri = this.config.listAllTasks.replace('#project#', projectID);

		self.panes.hideAll();
		this.currentProjectID = projectID;
		self.isLoading($.get(uri, function(data)
		{
			self.finishedLoading();
			self.panes.listTasksView.display(data, function(id)
			{
				self.editTask(id);
			}, null, function()
			{
				self.createTask(projectID);
			});
		}));
	};

	this.showProjects = function()
	{
		var self = this;

		self.panes.hideAll();
		this.currentProjectID = null;
		self.isLoading($.get(this.config.listProjectsURI, function(data)
		{
			self.finishedLoading();
			self.panes.listProjectsView.display(data, function(id)
			{
				self.showTasks(id);
			}, function(id)
			{
				self.editProject(id);
			}, function()
			{
				self.createProject();
			});
		}));
	};
}

(function ($)
{
	$.fn.tasksAPI = function (options)
	{
		options = $.extend(
		{
			'action' : 'showProjects',
			'params' : []
		}, options);

		$(this).each(function()
		{
			var self = $(this);
			self.data('tasks', new TasksAPIClient(self));
			self.data('tasks').initialise();

			var tasks = self.data('tasks');
			if (tasks[options.action] == undefined)
				return;

			tasks[options.action].apply(tasks, options.params);
		});
	};
}(jQuery));

jQuery(document).ready(function ()
{
	$('#tasksContainer').tasksAPI({'action' : 'showProjects' });
});