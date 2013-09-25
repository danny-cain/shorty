function MenuInfo(caption, callback)
{
	this.caption = caption;
	this.callback = callback;
}

if (window.contextMenu == undefined)
{
	window.contextMenu =
	{
		menuElement : $('<div class="contextMenu" style="display: none; position: absolute; "></div>'),
		drawMenu : function(x, y, menuInfo, positioningCallback)
		{
			if (positioningCallback == undefined)
				positioningCallback = function(x, y, menuElement) { this.positionMenu(x, y); };

			this.menuElement.empty();
			for (var i = 0; i < menuInfo.length; i ++)
			{
				var element = $('<div class="menuItem">' + menuInfo[i].caption + '</div>');
				element.bind('click', menuInfo[i].callback);
				this.menuElement.append(element);
			}

			positioningCallback.call(this, x, y, this.menuElement);
			this.menuElement.show();
		},
		positionMenu : function(x, y)
		{
			this.menuElement.css('left', x);
			this.menuElement.css('top', y);
		},
		closeMenu : function()
		{
			this.menuElement.hide();
		}
	};

	$(document).ready(function()
	{
		$('body').append(window.contextMenu.menuElement).on('click.contextMenu', function()
		{
			window.contextMenu.closeMenu();
		}).on('contextmenu', function(e)
		{
			window.contextMenu.closeMenu();
		});
	});
}