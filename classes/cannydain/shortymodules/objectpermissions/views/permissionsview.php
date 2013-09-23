<?php

namespace CannyDain\ShortyModules\ObjectPermissions\Views;

use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Lib\ObjectPermissions\ObjectPermissionsManagerInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\GUIDConsumer;
use CannyDain\Shorty\Consumers\ObjectPermissionsConsumer;
use CannyDain\Shorty\Views\ShortyView;
use CannyDain\ShortyModules\ObjectPermissions\Manager\ObjectPermissionsManager;
use CannyDain\ShortyModules\ObjectPermissions\Models\PermissionModel;

class PermissionsView extends ShortyView implements ObjectPermissionsConsumer, GUIDConsumer
{
    /**
     * @var ObjectPermissionsManager
     */
    protected $_permissionsManager;

    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    /**
     * @var Route
     */
    protected $_saveRoute;

    /**
     * @var Route
     */
    protected $_searchRoute;

    /**
     * @var PermissionModel[]
     */
    protected $_permissions = array();

    protected $_subjectIsConsumer = false;
    protected $_subjectGUID = '';

    public function display()
    {
        echo '<h2>Permissions for '.$this->_guids->getName($this->_subjectGUID).'</h2>';

        $class = 'consumerForm';
        if ($this->_subjectIsConsumer)
            $class = 'objectForm';

        echo '<div class="'.$class.'">';
            /* Output template display */
            echo '<div style="display: none;" class="permissionTemplate">';
                $perm = new PermissionModel();
                $this->_displayPermission($perm);
            echo '</div>';

            foreach ($this->_permissions as $permission)
                $this->_displayPermission($permission);

            $this->_displayAddForm();
            $this->_displaySaveButton();
        echo '</div>';

        $this->_writeScripts();
    }

    protected function _displaySaveButton()
    {
        echo '<div style="text-align: right;">';
            echo '<button class="savePermissions">Update Permissions</button>';
        echo '</div>';
    }

    protected function _displayPermission(PermissionModel $permission)
    {
        if ($this->_subjectIsConsumer)
            $this->_displayObjectPermissions($permission);
        else
            $this->_displayConsumerPermissions($permission);
    }

    protected function _displayAddForm()
    {
        echo '<div class="addForm">';
            echo '<input type="text" name="search" />';
        echo '</div>';
    }

    protected function _writeScripts()
    {
        static $_consumerBindingExecuted = false;
        static $_objectBindingExecuted = false;

        if ($this->_subjectIsConsumer && $_consumerBindingExecuted)
            return;

        if (!$this->_subjectIsConsumer && $_objectBindingExecuted)
            return;

        if ($this->_subjectIsConsumer)
        {
            $class = 'objectForm';
            $_consumerBindingExecuted = true;
        }
        else
        {
            $class = 'consumerForm';
            $_objectBindingExecuted = true;
        }

        $searchURI = $this->_router->getURI($this->_searchRoute);
        $saveURI = $this->_router->getURI($this->_saveRoute);
        $guid = base64_encode($this->_subjectGUID);

        echo <<<HTML
<script type="text/javascript">
    $(document).ready(function()
    {
        var container = $('.{$class}');

        $('.savePermissions', container).unbind('click.permissions').bind('click.permissions', function()
        {
            var button = $(this);

            button.attr('disabled', 'disabled');
            var permissions = {};
            var saveURI = "$saveURI";

            $('input[type="text"]', container).each(function()
            {
                console.log(this);
                var input = $(this);
                var val = input.val();
                var guid = input.attr('data-guid');

                if (guid == undefined)
                    return;

                if (guid == '')
                    return;

                permissions["permission_" + guid] = val;
            });

            permissions.guid = "$guid";
            $.post(saveURI, permissions, function(data)
            {
                button.removeAttr('disabled');
            })
        });

        var jqAjax = null;

        $('.addForm [name="search"]', container).shortyAutocomplete(
        {
            fetchData : function(query, callback)
            {
                if (jqAjax != null)
                    jqAjax.abort();

                jqAjax = $.get("{$searchURI}",
                {
                    term : query
                }, function(data)
                {
                    callback(data);
                });
            },
			selectItem : function(data)
			{
                // id, guid, name
                this.val('');
                var newRow = $($('.permissionTemplate', container).children().get(0)).clone();

                var name = data.name;
                var guid = data.guid;
                var id = data.id;

                $('.consumerName', newRow).text(name);
                $('[type="text"]', newRow).val('');
                $('[type="text"]', newRow).attr('data-guid', guid);
                $('[type="text"]', newRow).addClass('permissions');

                this.before(newRow);
			},
			createResultItem : function(data)
			{
		        return data.name;
            },
            repositionResults : function(resultsPane)
			{
				resultsPane.css('position', 'absolute');
				resultsPane.css('left', this.offset().left);
				resultsPane.css('top', this.offset().top + this.outerHeight());
			}
        });
    });
</script>
HTML;

    }

    protected function _displayConsumerPermissions(PermissionModel $permission)
    {
        echo '<div class="consumerPermissionsRow" data-consumer-guid="'.$permission->getConsumerGUID().'">';
            $name = $this->_guids->getName($permission->getConsumerGUID());
            echo '<span class="consumerName">';
                echo $name;
            echo '</span>';

            echo '<span class="permissions">';
                $permissions = $permission->getPermissions();
                echo '<input type="text" data-guid="'.base64_encode($permission->getConsumerGUID()).'" name="permissions_'.$permission->getConsumerGUID().'" value="'.$permissions.'" />';
            echo '</span>';
        echo '</div>';
    }

    protected function _displayObjectPermissions(PermissionModel $permission)
    {
        echo '<div class="consumerPermissionsRow" data-object-guid="'.$permission->getObjectGUID().'">';
            $name = $this->_guids->getName($permission->getObjectGUID());
            echo '<span class="objectName">';
                echo $name;
            echo '</span>';

            echo '<span class="permissions">';
                $permissions = $permission->getPermissions();
                echo '<input type="text" data-guid="'.base64_encode($permission->getObjectGUID()).'" name="permissions_'.$permission->getObjectGUID().'" value="'.$permissions.'" />';           echo '</span>';
        echo '</div>';
    }

    public function subjectIsConsumer($isConsumer)
    {
        $this->_subjectIsConsumer = $isConsumer;
    }

    public function setSubjectGUID($subjectGUID)
    {
        $this->_subjectGUID = $subjectGUID;
    }

    public function getSubjectGUID()
    {
        return $this->_subjectGUID;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $groupSearchRoute
     */
    public function setSearchRoute($groupSearchRoute)
    {
        $this->_searchRoute = $groupSearchRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getSearchRoute()
    {
        return $this->_searchRoute;
    }

    public function setPermissions($permissions)
    {
        $this->_permissions = $permissions;
    }

    public function getPermissions()
    {
        return $this->_permissions;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $saveRoute
     */
    public function setSaveRoute($saveRoute)
    {
        $this->_saveRoute = $saveRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getSaveRoute()
    {
        return $this->_saveRoute;
    }

    public function consumeObjectPermissionsManager(ObjectPermissionsManagerInterface $manager)
    {
        if ($manager instanceof ObjectPermissionsManager)
            $this->_permissionsManager = $manager;
        else
            throw new \Exception("Invalid Permissions Manager");
    }

    public function consumeGUIDManager(GUIDManagerInterface $guidManager)
    {
        $this->_guids = $guidManager;
    }
}