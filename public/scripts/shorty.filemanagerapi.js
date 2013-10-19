if (window.shorty == undefined)
	window.shorty = {};

if (window.shorty.apiClients == undefined)
	window.shorty.apiClients = {};

window.shorty.apiClients.FileManager = function(options)
{
	this.options = $.extend(
	{
		parentElement : $(document),
		fileManagerSelectFileURI : "/cannydain-shorty-filemanager-controllers-filemanagercontroller/browseframe?fileActions[select]=Select",
		fileManagerManageURI : "/cannydain-shorty-filemanager-controllers-filemanagercontroller/browseframe"
	}, options);

	this._currentSelectCallback = function(file)
	{
		return false;
	};

	this.dialog = $('<div style="display: none;" class="fileManager dialog"></div>');
	this.filemanagerFrame = $('<iframe src=""></iframe>');
	this.dialogTitleBar = $('<div class="titleBar"></div>');
	this.dialogTitle = $('<div class="title">File Manager</div>');
	this.dialogCloseButton = $('<div class="close">X</div>');

	this.dialogTitleBar.append(this.dialogTitle);
	this.dialogTitleBar.append(this.dialogCloseButton);
	this.dialog.append(this.dialogTitleBar);

	this.dialog.append(this.filemanagerFrame);

	this.overlay = this.options.parentElement;

	this.overlay.shortyLoadScreen();
	$('body').append(this.dialog);

	var self = this;

	this.dialogCloseButton.on("click", function()
	{
		self.dialog.hide();
		self.overlay.shortyLoadScreen({ method : "hide" });
	});

	this.selectFile = function(callback)
	{
		this._currentSelectCallback = callback;
		this.filemanagerFrame.attr('src', this.options.fileManagerSelectFileURI);
		this.overlay.shortyLoadScreen({ "method" : "show" });
		this.dialog.show();
		this.repositionDialog();
	};

	this.manageFiles = function()
	{
		this._currentSelectCallback = function(file) { return false; };
		this.filemanagerFrame.attr('src', this.options.fileManagerManageURI);
		this.overlay.shortyLoadScreen({ "method" : "show" });
		this.dialog.show();
		this.repositionDialog();
	};

	this.repositionDialog = function()
	{
		console.log(window);
		var windowWidth = window.outerWidth;
		var windowHeight = window.outerHeight;

		var left = (windowWidth - this.dialog.width()) / 2;
		var top = (windowHeight - this.dialog.height()) / 2;

		this.dialog.css('position', 'fixed')
			       .css('left', left)
				   .css('top', top);
	};

	window.addEventListener("message", function(e)
	{
		var origin = window.location.protocol + "//" + window.location.host;

		if (origin != e.origin)
		{
			return;
		}

		var data = e.data;

		switch(data.message)
		{
			case 'select':
				if (self._currentSelectCallback(data.file) === false)
					return;

				self.overlay.shortyLoadScreen({ "method" : "hide" });
				self.dialog.hide();
				break;
		}
	});
};