if (window.shorty == undefined)
	window.shorty = {};

if (window.shorty.models == undefined)
	window.shorty.models = {};

if (window.shorty.apiClients == undefined)
	window.shorty.apiClients = {};

if (window.shorty.constants == undefined)
	window.shorty.constants = {};

window.shorty.constants.FINANCE_TRANS_TYPE_TRANSACTION = 't';
window.shorty.constants.FINANCE_TRANS_TYPE_BALANCE = 'b';

window.shorty.models.Account = function(id, name)
{
	this.id = id;
	this.name = name;
};

window.shorty.models.Transaction = function(id, source, destination, date, detail, amount)
{
	this.id = id;
	this.source = source;
	this.destination = destination;
	this.date = date;
	this.detail = detail;
	this.amount= amount;
};

window.shorty.apiClients.Finance = function()
{
	this.endpoints =
	{
		listAccounts : "#listAccountsURI#",
		getBalance : "#getBalanceURI#",
		createTransaction : "#createTransactionURI#",
		getAccountDetails : '#getAccountDetailsForDateURI#',
		balanceAccount : '#balanceAccountURI#',
		lastBalanceDate : '#getLastBalanceDate#'
	};

	this.getLastBalanceDate = function(accountID, callback)
	{
		var uri = this.endpoints.lastBalanceDate.replace('#account#', accountID);

		this._get(uri, {}, function(data)
		{
			if (data.status == 'ok')
				callback(data.date);
		}, function()
		{

		});
	};

	this.getAccountDetails = function(accountID, startDate, endDate, callback)
	{
		var uri = this.endpoints.getAccountDetails.replace("#account#", accountID).replace("#start#", startDate).replace("#end#", endDate);

		this._get(uri, {}, function(data)
		{
			if (data.status == "ok")
			{
				callback(data.account, data.balance, data.transactions, data.start, data.end);
			}
		}, function()
		{

		});
	};

	this.getAllAccounts = function(callback)
	{
		var uri = this.endpoints.listAccounts;

		this._get(uri, {}, function(data)
		{
			callback(data);
		}, function()
		{

		});
	};

	this.getBalance = function(account, date, callback)
	{
		var uri = this.endpoints.getBalance.replace("#account#", account).replace("#date#", date);

		this._get(uri, {}, function(data)
		{
			callback(data.balance);
		}, function()
		{

		});
	};

	this.balanceAccount = function(id, callback)
	{
		var uri = this.endpoints.balanceAccount.replace('#account#', id);

		this._post(uri, {}, function(data)
		{
			callback();
		});
	};

	this.transfer = function(source, destination, amount, detail, callback)
	{
		var uri = this.endpoints.createTransaction;

		this._post(uri,
		{
			source : source,
			dest : destination,
			amount : amount,
			description : detail
		}, function(data)
		{
			callback(data);
		}, function(data)
		{

		});
	};

	this._post = function(url, data, successCallback, errorCallback)
	{
		$.ajax(url,
		{
			cache : false,
			context : this,
			type : "POST",
			data : data,
			error : function(jqXHR, status, error)
			{
				errorCallback();
			},
			success : function(data)
			{
				successCallback(data);
			}
		});
	};

	this._get = function(url, data, successCallback, errorCallback)
	{
		$.ajax(url,
		{
			cache : false,
			context : this,
			type : "GET",
			data : data,
			error : function(jqXHR, status, error)
			{
				errorCallback();
			},
			success : function(data)
			{
				successCallback(data);
			}
		});
	};
};