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
						this._loadingScreen.css('position', 'absolute')
										   .css('left', this._target.position().left)
										   .css('top', this._target.position().top)
										   .css('width', this._target.outerWidth())
										   .css('height', this._target.outerHeight());
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