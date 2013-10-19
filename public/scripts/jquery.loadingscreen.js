(function ($)
{
	$.fn.shortyLoadScreen = function (options)
	{
		options = $.extend(
		{
			method : "initialise",
			content : ""
		}, options);

		$(this).each(function()
		{
			var target = $(this);

			if (target.data('loadingScreen') == undefined)
			{
				var loadScreen =
				{
					_options : options,
					_target : target,
					_loadingScreen : $(),
					initialise : function()
					{
						this._loadingScreen = $('<div class="loadingScreen" style="display: none;"></div>');
						this._loadingScreen.html(this._options.content);

						$('body').append(this._loadingScreen);
					},
					show : function()
					{
						var width = this._target.width();
						var height = this._target.height();
						var left = 0;
						var top = 0;

						try
						{
							left = this._target.position().left;
							top = this._target.position().top;
						}
						catch(error) {}

						if (this._target.outerWidth() != undefined)
							width = this._target.outerWidth();

						if (this._target.outerHeight() != undefined)
							height = this._target.outerHeight();

						if (this._target.get(0) == window)
							this._loadingScreen.css('position', 'fixed');
						else
							this._loadingScreen.css('position', 'absolute');

						this._loadingScreen.css('left', left);
						this._loadingScreen.css('top', top);
						this._loadingScreen.css('width', width);
						this._loadingScreen.css('height', height);

						this._loadingScreen.show();
					},
					hide : function()
					{
						this._loadingScreen.hide();
					}
				};

				target.data('loadingScreen', loadScreen);
				loadScreen.initialise();
			}
			else
			{
				loadScreen = target.data('loadingScreen');
			}

			switch(options.method)
			{
				case 'show':
					loadScreen.show();
					break;
				case 'hide':
					loadScreen.hide();
					break;
			}
		});
		return this;
	};
}(jQuery));