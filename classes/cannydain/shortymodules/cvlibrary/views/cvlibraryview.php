<?php

namespace CannyDain\ShortyModules\CVLibrary\Views;

use CannyDain\Shorty\Views\ShortyView;

class CVLibraryView extends ShortyView
{
    protected $_scriptURI = '';

    public function display()
    {
        echo '<script type="text/javascript" src="'.$this->_scriptURI.'"></script>';

        echo <<<HTML
        <div style="display: none; " class="cvListPane">
            <div class="panelButtons">
                <div class="button addCV">Add CV</div>
            </div>

            <div style="display: none;" class="cvViewTemplate">
                <span class="name"></span>
                <span class="button edit">Edit</span>
                <span class="button delete">Delete</span>
                <span class="button download">Download</span>
            </div>
        </div>

        <div style="display: none; " class="editCVPane">
            <div class="panelButtons">
                <button class="button saveCV">Save</button>
                <button class="button cancelCVEdit">Cancel</button>
            </div>

            <div>
                <div class="fieldCaption">Title:</div>
                <div class="fieldInput"><input type="text" class="cvTitle" /></div>
            </div>

            <div>
                <div class="fieldCaption">Page Title:</div>
                <div class="fieldInput"><input type="text" class="pageTitle" /></div>
            </div>

            <div>
                <div class="fieldCaption">Full Name:</div>
                <div class="fieldInput"><input type="text" class="name" /></div>
            </div>

            <div>
                <div class="fieldCaption">Contact Number:</div>
                <div class="fieldInput"><input type="text" class="number" /></div>
            </div>

            <div>
                <div class="fieldCaption">Address:</div>
                <div class="fieldInput"><textarea class="address" ></textarea></div>
            </div>

            <div>
                <div class="fieldCaption">Hobbies and Interests:</div>
                <div class="fieldInput"><textarea class="hobbies" ></textarea></div>
            </div>

            <div>
                <div class="fieldCaption">About Yourself:</div>
                <div class="fieldInput"><textarea class="about" ></textarea></div>
            </div>

            <div>
                <div class="fieldCaption">
                    Qualifications:
                    <div class="button addQualification">Add Qualification</div>
                </div>

                <div class="fieldInput">
                    <table class="qualifications">
                        <tr>
                            <th class="courseName">Course</th>
                            <th class="courseGrade">Grade</th>
                            <th class="courseLevel">Level</th>
                            <th class="courseYear">Year</th>
                        </tr>
                        <tr style="display: none" class="editQualificationTemplate">
                            <td class="courseName"><input type="text" class="course" /></td>
                            <td class="courseGrade"><input type="text" class="grade" /></td>
                            <td class="courseLevel"><input type="text" class="level" /></td>
                            <td class="courseYear"><input type="text" class="year" /></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div>
                <div class="fieldCaption">
                    Work Experience:
                    <div class="button addExperience">Add Experience</div>
                </div>

                <div class="fieldInput">
                    <table class="experience">
                        <tr>
                            <th class="experienceCompany">Company</th>
                            <th class="experienceStart">From</th>
                            <th class="experienceFinish">To</th>
                            <th class="experienceTitle">Job Title</th>
                            <th class="experienceDescription">Description</th>
                        </tr>

                        <tr class="experienceTemplate" style="display: none;">
                            <td class="experienceCompany"><input type="text" class="company" /></td>
                            <td class="experienceStart">
                                <select class="start_month">
                                    <option value="01">Jan</option>
                                    <option value="02">Feb</option>
                                    <option value="03">Mar</option>
                                    <option value="04">Apr</option>
                                    <option value="05">May</option>
                                    <option value="06">Jun</option>
                                    <option value="07">Jul</option>
                                    <option value="08">Aug</option>
                                    <option value="09">Sept</option>
                                    <option value="10">Oct</option>
                                    <option value="11">Nov</option>
                                    <option value="12">Dec</option>
                                </select>
                                <input type="text" class="start_year" />
                            </td>
                            <td class="experienceFinish">
                                <select class="end_month">
                                    <option value=""></option>
                                    <option value="01">Jan</option>
                                    <option value="02">Feb</option>
                                    <option value="03">Mar</option>
                                    <option value="04">Apr</option>
                                    <option value="05">May</option>
                                    <option value="06">Jun</option>
                                    <option value="07">Jul</option>
                                    <option value="08">Aug</option>
                                    <option value="09">Sept</option>
                                    <option value="10">Oct</option>
                                    <option value="11">Nov</option>
                                    <option value="12">Dec</option>
                                </select>
                                <input type="text" class="end_year" />
                            </td>
                            <td class="experienceTitle"><input type="text" class="title" /></td>
                            <td class="experienceDescription"><textarea class="description"></textarea></td>
                        </tr>

                    </table>
                </div>
            </div>

            <div class="panelButtons">
                <button class="button saveCV">Save</button>
                <button class="button cancelCVEdit">Cancel</button>
            </div>
        </div>
HTML;

        echo <<<HTML
<script type="text/javascript">
    function ListCVsView()
    {
        this.cvRows = [];
        this.container = $('.cvListPane');
        this.templateRow = $('.cvViewTemplate', this.container);
        this.addButton = $('.addCV', this.container);

        this.editCVCallback = function(id) {};
        this.deleteCVCallback = function(id) {};
        this.addCVCallback = function() {};
        this.downloadCVCallback = function(id) {};

        this.clear = function()
        {
            for (var i = 0; i < this.cvRows.length; i ++)
                this.cvRows[i].remove();

            this.cvRows = [];
        };

        this.show = function()
        {
            this.container.show();
        };

        this.hide = function()
        {
            this.container.hide();
        };

        this.display = function(cvs)
        {
            var view = this;

            this.clear();

            for (var i = 0; i < cvs.length; i ++)
            {
                var row = this.templateRow.clone();

                row.removeClass("cvViewTemplate").addClass("cv");
                row.show();
                row.attr('data-id', cvs[i].id);

                $('.name', row).text(cvs[i].title);
                $('.edit', row).attr('data-id', cvs[i].id);
                $('.delete', row).attr('data-id', cvs[i].id);
                $('.download', row).attr('data-id', cvs[i].id);

                $('.download', row).on('click', function()
                {
                    view.downloadCVCallback($(this).attr('data-id'));
                });

                $('.edit', row).on('click', function()
                {
                    view.editCVCallback.call(view, $(this).attr('data-id'));
                });

                $('.delete', row).on('click', function()
                {
                    view.deleteCVCallback.call(view, $(this).attr('data-id'));
                });

                this.container.append(row);
                this.cvRows.push(row);
            }
        };

        var view = this;

        this.addButton.on('click', function()
        {
            view.addCVCallback();
        });
    }

    function EditCVView()
    {
        this.qualificationViews = [];
        this.experienceViews = [];
        this.container = $('.editCVPane');
        this.fields =
        {
            title : $('.cvTitle', this.container),
            about : $('.about', this.container),
            hobbies : $('.hobbies', this.container),
            pageTitle : $('.pageTitle', this.container),
            save : $('.saveCV', this.container),
            name : $('.name', this.container),
            number : $('.number', this.container),
            address : $('.address', this.container),
            cancelEdit : $('.cancelCVEdit', this.container)
        };
        this.addQualificationButton = $('.addQualification', this.container);
        this.addExperienceButton = $('.addExperience', this.container);

        this.saveCallback = function(cv, qualifications) {};
        this.cancelCallback = function() {};
        this.currentCV = {};

        var view = this;

        this.clear = function()
        {
            this.fields.title.val('');
            for (var i = 0; i < this.qualificationViews.length; i ++)
                this.qualificationViews[i].remove();

            for (i = 0; i < this.experienceViews.length; i ++)
                this.experienceViews[i].remove();

            this.experienceViews = [];
            this.qualificationViews = [];
        };

        this.editCV = function(cv, qualifications, experience)
        {
            this.clear();

            this.currentCV = cv;
            this.fields.title.val(cv.title);
            this.fields.pageTitle.val(cv.pageTitle);
            this.fields.about.val(cv.about);
            this.fields.hobbies.val(cv.hobbies);
            this.fields.name.val(cv.name);
            this.fields.number.val(cv.number);
            this.fields.address.val(cv.address);

            var experienceContainer = $('.experience', this.container);
            var experienceTemplate = $('.experienceTemplate', this.container);

            var qualificationContainer = $('.qualifications', this.container);
            var qualificationTemplate = $('.editQualificationTemplate', qualificationContainer);

            for (var i = 0; i < qualifications.length; i ++)
            {
                var view = new EditQualificationView(qualifications[i], qualificationTemplate, qualificationContainer);

                view.show();
                this.qualificationViews.push(view);
            }

            for (i = 0; i < experience.length; i ++)
            {
                var expView = new EditExperienceView(experience[i], experienceTemplate, experienceContainer);

                expView.show();
                this.experienceViews.push(expView);
            }
        };

        this.addExperienceButton.on("click", function()
        {
            var experienceContainer = $('.experience', view.container);
            var experienceTemplate = $('.experienceTemplate', view.container);
            var experience = new shorty.models.WorkExperience(0, view.currentCV.id, "", "", "", "", "");

            var newView = new EditExperienceView(experience, experienceTemplate, experienceContainer);
            newView.show();

            view.experienceViews.push(newView);
        });

        this.addQualificationButton.on("click", function()
        {
            var qualificationContainer = $('.qualifications', view.container);
            var qualificationTemplate = $('.editQualificationTemplate', qualificationContainer);
            var qualification = new shorty.models.Qualification(0, view.currentCV.id, "", "", "", "");

            var newView = new EditQualificationView(qualification, qualificationTemplate, qualificationContainer);
            newView.show();
            view.qualificationViews.push(newView);
        });

        this.fields.save.on('click', function()
        {
            var qualifications = [];
            var experience = [];

            for (var i = 0; i < view.qualificationViews.length; i ++)
            {
                qualifications.push(view.qualificationViews[i].getQualification());
            }

            for (i = 0; i < view.experienceViews.length; i ++)
                experience.push(view.experienceViews[i].getExperience());

            view.currentCV.title = view.fields.title.val();
            view.currentCV.about = view.fields.about.val();
            view.currentCV.hobbies = view.fields.hobbies.val();
            view.currentCV.pageTitle = view.fields.pageTitle.val();
            view.currentCV.name = view.fields.name.val();
            view.currentCV.number = view.fields.number.val();
            view.currentCV.address = view.fields.address.val();

            view.saveCallback(view.currentCV, qualifications, experience);
        });

        this.fields.cancelEdit.on('click', function()
        {
            view.cancelCallback();
        });

        this.show = function() { this.container.show(); };
        this.hide = function() { this.container.hide(); };
    }

    function EditQualificationView(qualification, template, parent)
    {
        // setup row
        this.container = template.clone();
        parent.append(this.container);
        this.container.removeClass("editQualificationTemplate").addClass("qualification");
        this.container.attr('data-id', qualification.id);
        this.container.attr('data-cv', qualification.cv);

        this.fields =
        {
            'course' : $('.course', this.container),
            'grade' : $('.grade', this.container),
            'level' : $('.level', this.container),
            'year' : $('.year', this.container)
        };

        this.fields.course.val(qualification.course);
        this.fields.grade.val(qualification.grade);
        this.fields.level.val(qualification.level);
        this.fields.year.val(qualification.year);

        this.show = function()
        {
            this.container.show();
        };

        this.hide = function()
        {
            this.container.hide();
        };

        this.remove = function()
        {
            this.container.remove();
        };

        this.getQualification = function()
        {
            var id = this.container.attr('data-id');
            var cv = this.container.attr('data-cv');
            var course = this.fields.course.val();
            var grade = this.fields.grade.val();
            var level = this.fields.level.val();
            var year = this.fields.year.val();

            return new shorty.models.Qualification(id, cv, course, grade, level, year);
        }
    }

    function EditExperienceView(experience, viewTemplate, viewContainer)
    {
        this.container = viewTemplate.clone();
        viewContainer.append(this.container);


        this.fields =
        {
            'description' : $('.description', this.container),
            'start_month' : $('.start_month', this.container),
            'start_year' : $('.start_year', this.container),
            'end_month' : $('.end_month', this.container),
            'end_year' : $('.end_year', this.container),
            'title' : $('.title', this.container),
            'company' : $('.company', this.container)
        };

        this.container.attr("data-id", experience.id);
        this.container.attr("data-cv", experience.cv);

        this.fields.description.val(experience.description);
        this.fields.title.val(experience.title);
        this.fields.company.val(experience.company);

        if (experience.start == "")
            experience.start = "2013-01";

        var startDate = experience.start.split("-");
        this.fields.start_year.val(startDate[0]);
        this.fields.start_month.val(startDate[1]);


        if (experience.end == "")
        {
            this.fields.end_year.val("");
            this.fields.end_month.val("");
        }
        else
        {
            var endDate = experience.end.split("-");
            this.fields.end_year.val(endDate[0]);
            this.fields.end_month.val(endDate[1]);
        }


        this.show = function() { this.container.show(); };
        this.hide = function() { this.container.hide(); };
        this.remove = function() { this.container.remove(); };
        this.getExperience = function()
        {
            var id = this.container.attr('data-id');
            var cv = this.container.attr('data-cv');
            var description = this.fields.description.val();
            var start = this.fields.start_year.val() + "-" + this.fields.start_month.val();
            var end = this.fields.end_year.val() + "-" + this.fields.end_month.val();

            if (end == "-")
                end = "";

            var company = this.fields.company.val();
            var title = this.fields.title.val();

            return new shorty.models.WorkExperience(id, cv, description, start, end, company, title);
        };
    }

    function CVLibraryClient()
    {
        this.listView = new ListCVsView();
        this.controller = new window.shorty.apiClients.CVLibrary();
        this.editView = new EditCVView();

        var client = this;

        this.listView.downloadCVCallback = function(id)
        {
            var uri = client.controller.getCVDownloadURI(id);
            window.open(uri);
        };

        this.listView.deleteCVCallback = function(id)
        {
            client.controller.deleteCV(id, function(succeeded)
            {
                $.history.load("/");
            });
        };

        this.listView.addCVCallback = function()
        {
            $.history.load("/addCV");
        };

        this.editView.saveCallback = function(cv, qualifications, experience)
        {
            client.controller.saveCV(cv, function(succeeded, cv)
            {
                client.controller.bulkSaveQAndE(cv.id, qualifications, experience, function()
                {
                    $.history.load("/");
                });
            });
        };

        this.editView.cancelCallback = function()
        {
            $.history.load("/");
        };

        this.listView.editCVCallback = function(id)
        {
            $.history.load("/editCV/" + id);
        };

        this.editCV = function(id)
        {
            client.controller.getCV(id, function(cv)
            {
                client.controller.getQualifications(id, function(qualifications)
                {
                    client.controller.getWorkExperience(id, function(experience)
                    {
                        client.editView.editCV(cv, qualifications, experience);
                        client.editView.show();
                        client.listView.hide();
                    });
                });
            });
        };

        this.addCV = function()
        {
            var cv = new window.shorty.models.CV(0, "");

            client.editView.editCV(cv, [], []);
            client.editView.show();
            client.listView.hide();
        };

        this.listCVs = function()
        {
            //$.history.load("");
            this.controller.listCVs(function(cvs)
            {
                client.listView.display(cvs);
                client.listView.show();
                client.editView.hide();
            });
        };
    }

    $(document).ready(function()
    {
        var client = new CVLibraryClient();
        var contentWindow = $('.cvListPane').parent();
        var requestCount = 0;

        contentWindow.shortyLoadScreen(
        {
            content : "Loading"
        });

        client.controller.requestStartCallbacks.push(function(uri)
        {
            requestCount ++;

            if (requestCount > 1)
                return;

            contentWindow.shortyLoadScreen(
            {
                method : "show"
            });
        });

        client.controller.requestFinishCallbacks.push(function(uri)
        {
            requestCount --;
            if (requestCount > 0)
                return;

            contentWindow.shortyLoadScreen(
            {
                method : "hide"
            });
        });

        $.history.init(function(hash)
        {
            if (hash.substr(0, 8) == '/editCV/')
            {
                var id = parseInt(hash.substr(8));

                client.editCV(id);
            }
            else if (hash == "/addCV")
            {
                client.addCV();
            }
            else
            {
                client.listCVs();
            }
        }, { unescape : ",/"});
    });
</script>
HTML;

    }

    public function setScriptURI($scriptURI)
    {
        $this->_scriptURI = $scriptURI;
    }

    public function getScriptURI()
    {
        return $this->_scriptURI;
    }
}