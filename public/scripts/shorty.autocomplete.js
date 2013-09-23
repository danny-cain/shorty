(function ($)
{
	$.fn.shortyAutocomplete = function (options)
	{
		if (window.acResults == undefined)
		{
			window.acResults = $('<div style="display: none; " class="autocompleteResults"></div>');
			$('body').append(window.acResults);
		}

		var results = window.acResults;

		options = $.extend(
		{
			action : "initialise",
			fetchData : function(query, callback) {},
			selectItem : function(data) {},
			createResultItem : function(data) { return data.caption; },
			repositionResults : function(resultsPane)
			{
				resultsPane.css('position', 'absolute');
				resultsPane.css('left', this.position().left);
				resultsPane.css('top', this.position().top + this.outerHeight());
			}
		}, options);

		switch(options.action)
		{
			case 'close':
				results.hide();
				break;
			case 'initialise':
				$(this).each(function()
				{
					var element = $(this);

					element.data('shortyAutocomplete',
					{
						'fetchData' : options.fetchData,
						'selectItem' : options.selectItem,
						'createResultItem' : options.createResultItem,
						'repositionResults' : options.repositionResults
					});

					element.unbind('keyup.shortyAC').bind('keyup.shortyAC', function()
					{
						var self = $(this);
						var text = self.val();

						results.hide();
						if (text.length < 3)
							return;

						self.data('shortyAutocomplete').fetchData.call(self, text, function(data)
						{
							results.empty();
							//reposition

							results.hide();
							self.data('shortyAutocomplete').repositionResults.call(self, results);

							for (var i = 0; i < data.length; i ++)
							{
								var resultContents = self.data('shortyAutocomplete').createResultItem.call(self, data[i]);
								if (typeof resultContents != "object")
									resultContents = $("<span>" + resultContents + "</span>");
								var result = $("<div class=\"result\"></div>");
								result.append(resultContents);

								result.attr('data-item', JSON.stringify(data[i]));
								results.append(result);

								result.bind("click", function(e)
								{
									var data = JSON.parse($(this).attr("data-item"));
									results.hide();
									self.data("shortyAutocomplete").selectItem.call(self, data);

									e.stopPropagation();
								});
							}

							results.show();
						});
					});
				});
				break;
		}
		return this;
	};
}(jQuery));