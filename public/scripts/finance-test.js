function displayAccount(id)
{
	if (window.debit == undefined)
	{
		var container = $('<div></div>');
		window.debit = $('<div style="border-bottom: 1px solid black; display: inline-block; width: 49.5%; margin-right: 1%;">Debit</div>');
		window.credit = $('<div style="border-bottom: 1px solid black; display: inline-block; width: 49.5%; ">Credit</div>');
		window.transactions = $('<div></div>');

		container.append(window.debit);
		container.append(window.credit);
		container.append(window.transactions);

		$('body').append(container);
	}


	var formatData = function(data)
	{
		var ret = [];
		var thisSet = [];

		for (var i = 0; i < data.length; i ++)
		{
			if (data[i].type == 'b')
			{
				ret.push({'transactions' : thisSet, 'balance' : data[i]});
				thisSet = [];
			}
			else
				thisSet.push(data[i]);
		}

		if (thisSet.length > 0)
			ret.push({'transactions' : thisSet, 'balance' : null });

		return ret;
	};

	var createRow = function(date, detail, amount)
	{
		var row = $('<div></div>');
		var dateCell = $('<div style="display: inline-block; width: 30%; margin-right: 1%;"></div>');
		var detailCell = $('<div style="display: inline-block; width: 30%; margin-right: 1%;"></div>');
		var amountCell = $('<div style="display: inline-block; width: 30%; margin-right: 1%;"></div>');

		dateCell.text(date);
		detailCell.text(detail);
		amountCell.html('&pound;' + (amount / 100));

		row.append(dateCell);
		row.append(detailCell);
		row.append(amountCell);

		return row;
	}

	window.transactions.empty();

	$.get('/cannydain-shortymodules-finance-controllers-financeapicontroller/getaccountdetailsfordate/' + id + '/1970-01-01/2013-12-31', function(data)
	{
		var books = formatData(data.transactions);

		for (var i = 0; i < books.length; i ++)
		{
			var bookContainer = $('<div></div>');
			var debitContainer = $('<div style="display: inline-block; width: 49.5%; margin-right: 1%;"></div>');
			var creditContainer = $('<div style="display: inline-block; width: 49.5%; "></div>');
			bookContainer.append(debitContainer);
			bookContainer.append(creditContainer);

			window.transactions.append(bookContainer);

			for (var trans = 0; trans < books[i].transactions.length; trans ++)
			{
				var transaction = books[i].transactions[trans];
				var row = createRow(transaction.date, transaction.description, transaction.amount);

				if (transaction.source == id)
					creditContainer.append(row);
				else
					debitContainer.append(row);
			}

			if (books[i].balance == null)
				continue;

			var balCD = $('<div style="display: inline-block; width: 49.5%;"></div>');
			var balBD = $('<div style="display: inline-block; width: 49.5%;"></div>');
			var debitTotal = $('<div style="display: inline-block; width: 49.5%; margin-right: 1%;">=======</div>');
			var creditTotal = $('<div style="display: inline-block; width: 49.5%; ">=======</div>');

			if (books[i].balance.amount < 0)
			{
				books[i].balance.amount = books[i].balance.amount * -1;
				balBD.css('margin-left', '50.5%');
			}
			else
				balCD.css('margin-left', '50.5%');

			balCD.append(createRow(books[i].balance.date, "Balance C/D", books[i].balance.amount));
			balBD.append(createRow(books[i].balance.date, "Balance B/D", books[i].balance.amount));

			var cdRow = $('<div></div>');
			var bdRow = $('<div></div>');
			var totalRow = $('<div></div>');

			cdRow.append(balCD);
			bdRow.append(balBD);
			totalRow.append(debitTotal).append(creditTotal);

			window.transactions.append(cdRow);
			window.transactions.append(totalRow);
			window.transactions.append(bdRow);
		}
	});
}