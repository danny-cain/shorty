tinymce.PluginManager.add('fileManager', function(editor, url)
{
	editor.addButton('fileManager',
	{
		text : 'File Manager',
		icon : false,
		onclick : function()
		{
			console.log("file manager");
		}
	});

	editor.addMenuItem("fileManager",
	{
		text : "File Manager",
		context : "tools",
		onclick : function()
		{
			console.log("file manager");
		}
	});
});