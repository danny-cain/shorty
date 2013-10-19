tinymce.PluginManager.add('filemanager', function(editor, url) {
    // Add a button that opens a window

	var filemanager = new window.shorty.apiClients.FileManager();
	var insertLink = function(uri)
	{
		var content = editor.selection.getContent({format  : 'html'});

		if (content == '')
			content = prompt("Enter a title for this link");

		editor.insertContent("<a href=\"" + uri + "\">" + content + "</a>");
		return true;
	};

	var insertImage = function(uri)
	{
		editor.insertContent("<img src=\"" + uri + "\" />");
	};

	editor.addButton('insertinternalimage',
	{
		text: 'Browse Images',
        icon: false,
        onclick: function()
		{
			filemanager.selectFile(function(file)
			{
				if (file.webPage == '')
				{
					alert("Error: This file is not accessible from the internet");
					return false;
				}

				insertImage(file.webPath);
				return true;
			});
        }
	});

    editor.addButton('insertinternallink',
	{
        text: 'Browse Files',
        icon: false,
        onclick: function()
		{
			filemanager.selectFile(function(file)
			{
				if (file.webPage == '')
				{
					alert("Error: This file is not accessible from the internet");
					return false;
				}

				insertLink(file.webPath);
				return true;
			});
        }
    });

	// Adds a menu item to the tools menu
    editor.addMenuItem('insertinternalimage',
	{
        text: 'Browse Images',
        context: 'insert',
        onclick: function()
		{
			filemanager.selectFile(function(file)
			{
				if (file.webPage == '')
				{
					alert("Error: This file is not accessible from the internet");
					return false;
				}

				insertImage(file.webPath);
				return true;
			});
        }
    });

    // Adds a menu item to the tools menu
    editor.addMenuItem('insertinternlalink',
	{
        text: 'Browse Files',
        context: 'insert',
        onclick: function()
		{
			filemanager.selectFile(function(file)
			{
				if (file.webPage == '')
				{
					alert("Error: This file is not accessible from the internet");
					return false;
				}

				insertLink(file.webPath);
				return true;
			});
        }
    });
});