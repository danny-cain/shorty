if (window.shorty == undefined)
	window.shorty = {};

if (window.shorty.apiClients == undefined)
	window.shorty.apiClients = {};

if (window.shorty.models == undefined)
	window.shorty.models = {};

if (window.shorty.models.CV == undefined)
{
	window.shorty.models.CV = function(id, title)
	{
		this.id = id;
		this.title = title;
		this.about = "";
		this.hobbies = "";
		this.pageTitle = "";
		this.name = "";
		this.number = "";
		this.address = "";
	};

	window.shorty.models.WorkExperience = function(id, cv, description, start, end, company, title)
	{
		this.id = id;
		this.cv = cv;
		this.description = description;
		this.start = start;
		this.end = end;
		this.company = company;
		this.title = title;
	};

	window.shorty.models.Qualification = function(id, cv, course, grade, level, year)
	{
		this.id = id;
		this.cv = cv;
		this.course = course;
		this.grade = grade;
		this.level = level;
		this.year = year;
	};
}

if (window.shorty.apiClients.CVLibrary == undefined)
{
	window.shorty.apiClients.CVLibrary = function()
	{
		this.endpoints =
		{
			'saveCV' : '#saveCVURI#',
			'saveQualification' : '#saveQualificationURI#',
			'saveExperience' : '#saveWorkExperienceURI#',
			'getAllCVs' : '#getAllCVsURI#',
			'getCV' : '#getCVURI#',
			'getQualifications' : '#getQualificationsURI#',
			'getExperience' : '#getWorkExperienceURI#',
			'deleteCV' : '#deleteCVURI#',
			'deleteExperience' : '#deleteExperienceURI#',
			'deleteQualification' : '#deleteQualificationURI#',
			'download' : "#downloadURI#"
		};

		this.getCVDownloadURI = function(id)
		{
			return this.endpoints.download.replace("#id#", id);
		};

		this._notifyError = function(status, error)
		{

		};

		this._notifyFailure = function(message)
		{

		};

		this._notifyStartRequest = function(uri)
		{

		};

		this._notifyFinishRequest = function(uri)
		{

		};

		this._get = function(uri, data, successCallback, failCallback)
		{
			if (successCallback == undefined)
				successCallback = function() {};

			if (failCallback == undefined)
				failCallback = function() {};

			if (data == undefined)
				data = {};

			var context = this;

			this._notifyStartRequest(uri);
			$.ajax(uri,
			{
				cache : false,
				type : "GET",
				data : data,
				error : function(jqXHR, status, error)
				{
					context._notifyError(status, error);
					failCallback.call(context);
				},
				success : function(data)
				{
					successCallback.call(context, data);
				},
				complete : function()
				{
					context._notifyFinishRequest(uri);
				}
			});
		};

		this._post = function(uri, data, successCallback, failCallback)
		{
			if (successCallback == undefined)
				successCallback = function() {};

			if (failCallback == undefined)
				failCallback = function() {};

			if (data == undefined)
				data = {};

			var context = this;

			this._notifyStartRequest(uri);
			$.ajax(uri,
			{
				cache : false,
				type : "POST",
				data : data,
				error : function(jqXHR, status, error)
				{
					context._notifyError(status, error);
					failCallback.call(context);
				},
				success : function(data)
				{
					successCallback.call(context, data);
				},
				complete : function()
				{
					context._notifyFinishRequest(uri);
				}
			});
		};

		this.listCVs = function(callback)
		{
			var uri = this.endpoints.getAllCVs;

			this._get(uri, {}, function(data)
			{
				callback.call(this, data);
			});
		};

		this.deleteCV = function(id, callback)
		{
			var uri = this.endpoints.deleteCV.replace("#id#", id);

			this._post(uri, {}, function(data)
			{
				var succeeded = false;

				if (data.status != undefined && data.status == "ok")
					succeeded = true;

				if (!succeeded)
					this._notifyFailure(data.message);

				callback.call(this, succeeded);
			});
		};

		this.deleteQualification = function(id, callback)
		{
			var uri = this.endpoints.deleteQualification.replace("#id#", id);

			this._post(uri, {}, function(data)
			{
				var succeeded = false;

				if (data.status != undefined && data.status == "ok")
					succeeded = true;

				if (!succeeded)
					this._notifyFailure(data.message);

				callback.call(this, succeeded);
			});
		};

		this.deleteWorkExperience = function(id, callback)
		{
			var uri = this.endpoints.deleteExperience.replace("#id#", id);

			this._post(uri, {}, function(data)
			{
				var succeeded = false;

				if (data.status != undefined && data.status == "ok")
					succeeded = true;

				if (!succeeded)
					this._notifyFailure(data.message);

				callback.call(this, succeeded);
			});
		};

		this.saveWorkExperience = function(experience, callback)
		{
			var uri = this.endpoints.saveExperience;

			this._post(uri, experience, function(data)
			{
				var succeeded = false;
				var experience = null;


				if (data.status != undefined && data.status == "ok")
					succeeded = true;

				if (succeeded)
					experience = data.experience;

				if (!succeeded)
					this._notifyFailure(data.message);

				if (callback != undefined)
					callback.call(this, succeeded, experience);
			});
		};

		this.saveCV = function(cv, callback)
		{
			var uri = this.endpoints.saveCV;

			this._post(uri, cv, function(data)
			{
				var succeeded = false;
				var cv = null;

				if (data.status != undefined && data.status == "ok")
					succeeded = true;

				if (succeeded)
					cv = data.cv;

				if (!succeeded)
					this._notifyFailure(data.message);

				if (callback != undefined)
					callback.call(this, succeeded, cv);
			});
		};

		this.saveQualification = function(qualification, callback)
		{
			var uri = this.endpoints.saveQualification;

			this._post(uri, qualification, function(data)
			{
				var succeeded = false;
				var qualification = null;

				if (data.status != undefined && data.status == "ok")
					succeeded = true;

				if (succeeded)
					qualification = data.qualification;

				if (!succeeded)
					this._notifyFailure(data.message);

				if (callback != undefined)
					callback.call(this, succeeded, qualification);
			});
		};

		this.getCV = function(id, callback)
		{
			var uri = this.endpoints.getCV.replace("#id#", id);

			this._get(uri, {}, function(data)
			{
				callback.call(this, data);
			});
		};

		this.getQualifications = function(cv, callback)
		{
			var uri = this.endpoints.getQualifications.replace("#cv#", cv);

			this._get(uri, {}, function(data)
			{
				callback.call(this, data);
			});
		};

		this.getWorkExperience = function(cv, callback)
		{
			var uri = this.endpoints.getExperience.replace("#cv#", cv);

			this._get(uri, {}, function(data)
			{
				callback.call(this, data);
			});
		};
	};
}