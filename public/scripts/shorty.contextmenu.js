function MenuInfo(caption, callback, data)
{
	this.caption = caption;
	this.callback = callback;
	if (data == undefined)
		this.data = {};
	else
		this.data = data;
}

if (window.contextMenu == undefined)
{
	window.contextMenu =
	{
		menuElement : $('<div class="contextMenu" style="display: none; position: absolute; "></div>'),
		bindMenu : function(domElement, menuInfo)
		{
			var self = this;

			domElement.bind('click', function()
			{
				var data = {};

				try
				{
					data = JSON.parse($(this).attr('data-menuData'));
				}
				catch(error) {}

				menuInfo.callback.call(self, data);
			});
		},
		drawMenu : function(x, y, menuInfo, positioningCallback)
		{
			if (positioningCallback == undefined)
				positioningCallback = function(x, y, menuElement) { this.positionMenu(x, y); };

			this.menuElement.empty();
			for (var i = 0; i < menuInfo.length; i ++)
			{
				var element = $('<div class="menuItem">' + menuInfo[i].caption + '</div>');

				element.attr('data-menuData', JSON.stringify(menuInfo[i].data));

				this.bindMenu(element, menuInfo[i]);
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