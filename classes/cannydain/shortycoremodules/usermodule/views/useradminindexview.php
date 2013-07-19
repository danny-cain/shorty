<?php

namespace CannyDain\ShortyCoreModules\UserModule\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\ShortyCoreModules\UserModule\Models\UserModel;

class UserAdminIndexView extends HTMLView
{
    protected $_userSearchAPIURI = '';

    protected $_groupSearchAPIURI = '';

    protected $_createUserURI = '';

    protected $_editUserURITemplate = '';

    protected $_createGroupURITemplate = '';

    /**
     * @var UserModel[]
     */
    protected $_recentRegistrations = array();

    public function display()
    {
        echo '<div style="vertical-align: top; display: inline-block; width: 49%; margin-right: 0.5%; ">';
            echo '<h1>User Admnistration</h1>';
            $this->_displayUserSearch();

            if ($this->_createUserURI != '')
            {
                echo '<div><a href="'.$this->_createUserURI.'">[create user]</a></div>';
            }
            $this->_displayNewUsers();
        echo '</div>';

        echo '<div style="vertical-align: top; display: inline-block; width: 49%; ">';
            echo '<h1>Group Administration</h1>';
            $this->_displayGroupSearch();

            if ($this->_createGroupURITemplate != '')
            {
                echo '<div><a href="'.$this->_createGroupURITemplate.'">[create group]</a></div>';
            }
        echo '</div>';
    }

    protected function _displayNewUsers()
    {
        echo '<h1>Recent Registrations</h1>';

        foreach ($this->_recentRegistrations as $user)
        {
            $editURI = strtr($this->_editUserURITemplate, array('#id#' => $user->getId()));

            echo '<div>';
                echo '<a href="'.$editURI.'">'.$user->getUsername().'</a>';
            echo '</div>';
        }
    }

    protected function _displayGroupSearch()
    {
        echo '<div>';
            echo 'Search groups: <input type="text" id="groupSearch" /><br>';
        echo '</div>';

        echo <<<HTML
<script type="text/javascript">
    function GroupAutocomplete(element)
    {
        var self = this;

        this.uri = "{$this->_groupSearchAPIURI}";
        this.element = element;
        this.results = $('<div class="autocompleteResults"></div>');
        this.jqAjax = null;

        this.hideResults = function()
        {
            self.results.hide();
        };

        this.abort = function()
        {
            if (self.jqAjax != null)
                self.jqAjax.abort();

            self.jqAjax = null;
        };

        this.autocomplete = function(query)
        {
            self.abort();
            self.hideResults();

            var url = "{$this->_groupSearchAPIURI}".replace('#query#', query);

            self.jqAjax = $.get(url, function(data)
            {
                if (typeof(data) != 'object')
                    data = eval("(" + data + ")");

                self.results.empty();
                for (var i in data)
                {
                    if (!data.hasOwnProperty(i))
                        continue;

                    self.results.append('<a class="result" href="' + data[i].editURI + '">' + data[i].name + '</a>');
                }
                self.results.show();
            });
        };

        element.keyup(function()
        {
            self.hideResults();

            if (self.element.val().length < 3)
                return;

            self.autocomplete(self.element.val());
        });

        self.results.hide();
        $('body').append(self.results);
        self.results.css('position', 'absolute');

        self.results.css('left', self.element.position().left);
        self.results.css('top', self.element.position().top + self.element.height() + 5);
    }

    $(document).ready(function()
    {
        var groupSearch = $('#groupSearch');
        groupSearch.data('autocomplete', new GroupAutocomplete(groupSearch));
    });
</script>
HTML;
    }

    protected function _displayUserSearch()
    {
        echo '<div>';
            echo 'Search users: <input type="text" id="userSearch" /><br>';
        echo '</div>';

        echo <<<HTML
<script type="text/javascript">
    function GroupAutocomplete(element)
    {
        var self = this;

        this.uri = "{$this->_groupSearchAPIURI}";
        this.element = element;
        this.results = $('<div class="autocompleteResults"></div>');
        this.jqAjax = null;

        this.hideResults = function()
        {
            self.results.hide();
        };

        this.abort = function()
        {
            if (self.jqAjax != null)
                self.jqAjax.abort();

            self.jqAjax = null;
        };

        this.autocomplete = function(query)
        {
            self.abort();
            self.hideResults();

            var url = "{$this->_groupSearchAPIURI}".replace('#query#', query);

            self.jqAjax = $.get(url, function(data)
            {
                if (typeof(data) != 'object')
                    data = eval("(" + data + ")");

                self.results.empty();
                for (var i in data)
                {
                    if (!data.hasOwnProperty(i))
                        continue;

                    self.results.append('<a class="result" href="' + data[i].editURI + '">' + data[i].name + '</a>');
                }
                self.results.show();
            });
        };

        element.keyup(function()
        {
            self.hideResults();

            if (self.element.val().length < 3)
                return;

            self.autocomplete(self.element.val());
        });

        self.results.hide();
        $('body').append(self.results);
        self.results.css('position', 'absolute');
        self.results.css('left', self.element.position().left);
        self.results.css('top', self.element.position().top + self.element.height() + 5);
    }

    function Autocomplete(element)
    {
        var self = this;

        this.uri = "{$this->_userSearchAPIURI}";
        this.element = element;
        this.results = $('<div class="autocompleteResults"></div>');
        this.jqAjax = null;

        this.hideResults = function()
        {
            self.results.hide();
        };

        this.abort = function()
        {
            if (self.jqAjax != null)
                self.jqAjax.abort();

            self.jqAjax = null;
        };

        this.autocomplete = function(query)
        {
            self.abort();
            self.hideResults();

            var url = "{$this->_userSearchAPIURI}".replace('#query#', query);

            self.jqAjax = $.get(url, function(data)
            {
                if (typeof(data) != 'object')
                    data = eval("(" + data + ")");

                self.results.empty();
                for (var i in data)
                {
                    if (!data.hasOwnProperty(i))
                        continue;

                    self.results.append('<a class="result" href="' + data[i].editURI + '">' + data[i].username + '</a>');
                }
                self.results.show();
            });
        };

        element.keyup(function()
        {
            self.hideResults();

            if (self.element.val().length < 3)
                return;

            self.autocomplete(self.element.val());
        });

        self.results.hide();
        $('body').append(self.results);
        self.results.css('position', 'absolute');
        self.results.css('left', self.element.position().left);
        self.results.css('top', self.element.position().top + self.element.height() + 5);
    }

    $(document).ready(function()
    {
        var search = $('#userSearch');
        search.data('autocomplete', new Autocomplete(search));
    });
</script>
HTML;

    }

    public function setGroupSearchAPIURI($groupSearchAPIURI)
    {
        $this->_groupSearchAPIURI = $groupSearchAPIURI;
    }

    public function getGroupSearchAPIURI()
    {
        return $this->_groupSearchAPIURI;
    }

    public function setCreateGroupURITemplate($createGroupURITemplate)
    {
        $this->_createGroupURITemplate = $createGroupURITemplate;
    }

    public function getCreateGroupURITemplate()
    {
        return $this->_createGroupURITemplate;
    }

    public function setEditUserURITemplate($editUserURITemplate)
    {
        $this->_editUserURITemplate = $editUserURITemplate;
    }

    public function getEditUserURITemplate()
    {
        return $this->_editUserURITemplate;
    }

    public function setCreateUserURI($createUserURI)
    {
        $this->_createUserURI = $createUserURI;
    }

    public function getCreateUserURI()
    {
        return $this->_createUserURI;
    }

    public function setRecentRegistrations($recentRegistrations)
    {
        $this->_recentRegistrations = $recentRegistrations;
    }

    public function getRecentRegistrations()
    {
        return $this->_recentRegistrations;
    }

    public function setUserSearchAPIURI($userSearchAPIURI)
    {
        $this->_userSearchAPIURI = $userSearchAPIURI;
    }

    public function getUserSearchAPIURI()
    {
        return $this->_userSearchAPIURI;
    }
}