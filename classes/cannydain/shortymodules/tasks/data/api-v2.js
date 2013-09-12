if (window.shorty == undefined)
	window.shorty = {};

if (window.shorty.models == undefined)
	window.shorty.models = {};

if (window.shorty.controllers == undefined)
	window.shorty.controllers = {};

window.shorty.models.Project = function()
{
	this.id = 0;
	this.name = '';
};

window.shorty.models.Task = function()
{
	this.id = 0;
	this.title = '';
	this.shortDesc = '';
	this.longDesc = '';
	this.project = 0;
};

window.shorty.controllers.ProjectManagement = function(data)
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
		listAllTasks : "#listAllTasks#",
		searchTaskURI : "#searchTasks#"
	};

	data = $.extend(
	{
		listProjectsCallback : function(projects) {},
		listTasksCallback : function(tasks) {},
		getProjectCallback : function(project) {},
		getTaskCallback : function(task) {},
		editTaskCallback : function(task) {},
		editProjectCallback : function(project) {},
		startRequestCallback : function(uri) {},
		endRequestCallback : function(uri) {}
	}, data);

	this.callbacks =
	{
		listProjects : data.listProjectsCallback,
		listTasks : data.listTasksCallback,
		getProject : data.getProjectCallback,
		getTask : data.getTaskCallback,
		editTask : data.editTaskCallback,
		editProject : data.editProjectCallback,
		startRequest : data.startRequestCallback,
		endRequest : data.endRequestCallback
	};

	this._get = function(uri, data, callback)
	{
		var self = this;

		if (data == undefined)
			data = {};
		if (callback == undefined)
			callback = function() {};

		self.callbacks.startRequest(uri);
		$.get(uri, data, function(response)
		{
			self.callbacks.endRequest(uri);
			callback(response);
		});
	};

	this._post = function(uri, data, callback)
	{
		var self = this;

		if (data == undefined)
			data = {};
		if (callback == undefined)
			callback = function() {};

		self.callbacks.startRequest(uri);
		$.post(uri, data, function(response)
		{
			self.callbacks.endRequest(uri);
			callback(response);
		});
	};

	this.getProjects = function(callback)
	{
		var self = this;

		var uri = self.config.listProjectsURI;
		self._get(uri, {}, function(data)
		{
			self.callbacks.listProjects(data);
			if (callback != undefined)
				callback(data);
		});
	};

	this.searchTasks = function(searchTerm, callback)
	{
		var self = this;

		var uri = self.config.searchTaskURI.replace('#term#', searchTerm);
		var callerInfo =
		{
			method : "searchTasks",
			arguments : [searchTerm]
		};

		self._get(uri, {}, function(data)
		{
			self.callbacks.listTasks(data, callerInfo);
			if (callback != undefined)
				callback(data, callerInfo);
		});
	};

	this.getTasksByProject = function(projectID, callback)
	{
		var self = this;

		var uri = self.config.listAllTasks.replace('#project#', projectID);
		var callerInfo =
		{
			method : "getTasksByProject",
			arguments : [projectID]
		};

		self._get(uri, {}, function(data)
		{
			self.callbacks.listTasks(data, callerInfo);
			if (callback != undefined)
				callback(data, callerInfo);
		});
	};

	this.getTaskByID = function(id, callback)
	{
		var self = this;

		var uri = self.config.getTaskURI.replace('#id#', id);
		self._get(uri, {}, function(data)
		{
			self.callbacks.getTask(data);
			if (callback != undefined)
				callback(data);
		});
	};

	this.getProjectByID = function(id, callback)
	{
		var self = this;

		var uri = self.config.getProjectURI.replace('#id#', id);
		self._get(uri, {}, function(data)
		{
			self.callbacks.getProject(data);
			if (callback != undefined)
				callback(data);
		});
	};

	this.saveProject = function(project, callback)
	{
		var self = this;

		if (project.id < 1)
		{
			this.createProject(project, callback);
			return;
		}

		var uri = self.config.editProjectURI.replace('#id#', project.id);
		self._post(uri, project, function(data)
		{
			self.callbacks.editProject(data);
			if (callback != undefined)
				callback(data);
		});
	};

	this.saveTask = function(task, callback)
	{
		var self = this;

		if (task.id < 1)
		{
			this.createTask(task, callback);
			return;
		}

		var uri = self.config.editTaskURI.replace('#id#', task.id);
		self._post(uri, task, function(data)
		{
			self.callbacks.editTask(data);
			if (callback != undefined)
				callback(data);
		});
	};

	this.createTask = function(task, callback)
	{
		var self = this;

		var uri = self.config.createTaskURI;

		self._post(uri, task, function(data)
		{
			self.callbacks.editTask(data);
			if (callback != undefined)
				callback(data);
		});
	};

	this.createProject = function(project, callback)
	{
		var self = this;

		var uri = self.config.createProjectURI;
		self._post(uri, project, function(data)
		{
			self.callbacks.editProject(data);
			if (callback != undefined)
				callback(data);
		});
	};
};