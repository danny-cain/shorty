if (window.shorty == undefined)
	window.shorty = {};

if (window.shorty.apiClients == undefined)
	window.shorty.apiClients = {};

if (window.shorty.views == undefined)
	window.shorty.views = {};

if (window.shorty.models == undefined)
	window.shorty.models = {};

window.shorty.models.FileManagerCallbackManager =
{
	_callbacks : [],
	registerCallback : function(callback)
	{
		this.init();

		var id = this._callbacks.length;
		this._callbacks.push(callback);

		return id;
	},
	executeCallback : function()
	{
		var id = arguments[0];
		var args = [];

		for (var i = 1; i < arguments.length; i ++)
			args.push(arguments[i]);

		if (!this._callbacks.hasOwnProperty(id))
			return;

		this._callbacks[id].apply(this, args);
	},
	getCallbackName : function()
	{
		this.init();
		return "window.fmCallback";
	},
	init : function()
	{
		if (window.fmCallback == undefined)
		{
			window.fmCallback = function()
			{
				window.shorty.models.FileManagerCallbackManager.executeCallback.apply(window.shorty.models.FileManagerCallbackManager, arguments);
			};
		}
	}
};

window.shorty.models.FileEntry = function(name, mimeType, path, type)
{
	this.name = name;
	this.mimeType = mimeType;
	this.path = path;
	this.type = type;
};

window.shorty.apiClients.FileManager = function(config)
{
	this.config = $.extend(
	{
		"list" : "",
		"delete" : "",
		"upload" : ""
	}, config);

	this.list = function(path, callback)
	{
		$.ajax(this.config.list,
		{
			success : function(data)
			{
				if (data.files == undefined || data.directories == undefined)
				{
					alert("Failed to fetch directory listing");
					callback([], []);
				}
				else
				{
					var files = [];
					var directories = [];

					for (var i = 0; i < data.directories.length; i ++)
					{
						var name = data.directories[i].name;
						var mime = data.directories[i].mimeType;
						var path = data.directories[i].path;
						var type = data.directories[i].type;

						directories.push(new window.shorty.models.FileEntry(name, mime, path, type));
					}
					for (i = 0; i < data.files.length; i ++)
					{
						var name = data.files[i].name;
						var mime = data.files[i].mimeType;
						var path = data.files[i].path;
						var type = data.files[i].type;

						files.push(new window.shorty.models.FileEntry(name, mime, path, type));
					}

					callback(directories, files);
				}
			},
			error : function(data)
			{
				alert("Failed to retrieve directory listing");
				callback([], []);
			},
			data :
			{
				"path" : path
			}
		});
	};

	this.delete = function(path, callback)
	{
		callback("Ok");
	};

	this.getUploadURI = function()
	{
		return this.config.upload;
	};
};

window.shorty.views.FileManagerView = function(config)
{
	var view = this;

	var uploadCallbackID = null;
	this.currentPath = "/";
	this.config = $.extend(
	{
		client : new window.shorty.apiClients.FileManager()
	},config);

	this._actions =
	{
		"open" :
		{
			name : "open",
			caption : "Open",
			isValid : function(target) { return target.type == "D"; },
			execute : function(target)
			{
				this.display(target.path + target.name);
			}
		},
		"delete" :
		{
			name : "delete",
			caption : "Delete",
			isValid : function(target) { return true; },
			execute : function(target)
			{
				console.log("Delete " + target.path + target.name);
			}
		}
	};

	this.overlay = $('<div style="display: none; width: 100%; height: 100%; position: fixed; left: 0; top: 0; " class="overlay"></div>');
	this.dialog = $('<div style="display: none; " class="fileManager dialog"></div>');

	$('body').append(this.overlay).append(this.dialog);

	this._setupDialog = function()
	{
		this.dialog.append('<div class="close">X</div>');
		this.dialog.append('<div class="title"></div>');
		this.dialog.append('<div class="contents"></div>');
		this.dialog.append('<iframe style="height: 70px; width: 100%; border: none;" class="upload"></iframe>');

		$('.close', this.dialog).on("click", function()
		{
			view.close();
		});
	};
	this._setupDialog();

	this.addAction = function(name, caption, isValidCallback, executeCallback)
	{
		this._actions[name] =
		{
			name : name,
			caption : caption,
			isValid : isValidCallback,
			execute : executeCallback
		};
	};

	this.refresh = function() { this.display(this.currentPath); };

	this.updateDialogTitle = function()
	{
		var title = $('.title', this.dialog);
		var self = this;

		title.empty();

		var titleParts = this.currentPath.split("/");
		var currentURI = '';

		for (var i = 0; i < titleParts.length; i ++)
		{
			currentURI += titleParts[i] + "/";

			if (currentURI.length > 1)
			{
				var segment = $('<span data-path="' + currentURI + '" class="titleLink">' + titleParts[i] + '</span>');
				segment.on("click", function()
				{
					self.display($(this).attr('data-path'));
				});
				title.append(segment);
			}
			else
			{
				var segment = $('<span data-path="/" class="titleLink">&lt;root&gt;</span>');
				segment.on("click", function()
				{
					self.display($(this).attr('data-path'));
				});
				title.append(segment);
			}

			if (i + 1 < titleParts.length)
				title.append("<span>/</span>");
		}
	};

	this.display = function(path)
	{
		var self = this;

		this.currentPath = path;
		var contents = $('.contents', this.dialog);

		contents.empty();
		contents.append('<div class="loading">Loading</div>');

		self.overlay.show();
		self.dialog.show();
		self.repositionDialog();

		this.config.client.list(path, function(directories, files)
		{
			self.updateDialogTitle();

			contents.empty();

			for (var i = 0; i < directories.length; i ++)
				contents.append(self._getFileView(directories[i]));

			for (var i = 0; i < files.length; i ++)
				contents.append(self._getFileView(files[i]));

			self.setupUploadForm();
			self.repositionDialog();
		});
	};

	this._initialiseUpload = function()
	{
		var self = this;

		if (this.uploadCallbackID == null)
		{
			this.uploadCallbackID = window.shorty.models.FileManagerCallbackManager.registerCallback(function(status)
			{
				if (status != "Ok")
				{
					alert("Upload Failed");
				}

				self.refresh();
			});
		}
	};

	this.setupUploadForm = function()
	{
		this._initialiseUpload();

		var container = $('.upload', this.dialog);
		var doc = container.contents();
		var callbackFunction = window.shorty.models.FileManagerCallbackManager.getCallbackName();
		var body = $('body', doc);

		if (body.size() == 0)
		{
			doc.get(0).write('<!DOCTYPE html><html></html>');
			$('html', doc).append('<head></head>').append('<body></body>');
			body = $('body', doc);
		}

		body.empty();
		var form = $('<form method="post" enctype="multipart/form-data" action="' + this.config.client.getUploadURI() + '"></form>');
		var directory = $('<input type="hidden" name="dir" value="' + this.currentPath + '" />');
		var callback = $('<input type="hidden" name="callback" value="' + callbackFunction + '" />');
		var callbackID = $('<input type="hidden" name="callbackID" value="' + this.uploadCallbackID + '" />');
		var file = $('<input type="file" name="file" />');
		var button = $('<input type="submit" value="Upload" />');

		form.append(directory);
		form.append(callback);
		form.append(callbackID);
		form.append(file);
		form.append(button);
		body.append(form);
	};

	this.close = function()
	{
		this.dialog.hide();
		this.overlay.hide();
	};

	this.repositionDialog = function()
	{
		var left = ($(window).width() - this.dialog.width()) / 2;
		var top = ($(window).height() - this.dialog.height()) / 2;

		this.dialog.css('position', 'fixed').css('left', left).css('top', top);
	};

	this._getFileView = function(file)
	{
		var ret = $('<div class="fileElement"></div>');

		if (file.type == "D")
			ret.addClass("directory");
		else
			ret.addClass("file");

		var name = $('<div class="name"></div>');
		if (file.type == "D")
			name.text("/" + file.name);
		else
			name.text(file.name);

		ret.attr('title', file.name);
		ret.append(name);

		var actions = this._getActions(file);
		for (var i = 0; i < actions.length; i ++)
		{
			ret.append(actions[i]);
		}

		return ret;
	};

	this._executeAction = function(file, action)
	{
		if (!this._actions.hasOwnProperty(action))
			return;

		var selectedAction = this._actions[action];

		if (!selectedAction.isValid.call(this, file))
			return;

		selectedAction.execute.call(this, file);
	};

	this._getActions = function(target)
	{
		var ret = [];

		for (var key in this._actions)
		{
			if (!this._actions.hasOwnProperty(key))
				continue;

			if (!this._actions[key].isValid(target))
				continue;

			var action = $('<div data-action="' + key + '" data-target="' + JSON.stringify(target) + '" class="action">' + this._actions[key].caption + '</div>');
			action.data('target', target);

			action.on("click", function()
			{
				var file = $(this).data('target');

				view._executeAction(file, $(this).attr('data-action'));
			});

			ret.push(action);
		}

		return ret;
	};
};