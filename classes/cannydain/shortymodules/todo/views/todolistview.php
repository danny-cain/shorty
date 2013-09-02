<?php

namespace CannyDain\ShortyModules\Todo\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Views\ShortyView;
use CannyDain\ShortyModules\Todo\Models\TodoEntry;

class TodoListView extends ShortyView
{
    /**
     * @var TodoEntry[]
     */
    protected $_entries = array();

    /**
     * @var Route
     */
    protected $_createRoute;

    /**
     * @var Route
     */
    protected $_editRoute;

    /**
     * @var Route
     */
    protected $_deleteRoute;

    /**
     * @var Route
     */
    protected $_completeRoute;

    public function display()
    {
        echo '<h1>All Tasks</h1>';

        foreach ($this->_entries as $entry)
            $this->_displayEntry($entry);

        echo $this->_getActionButton($this->_router->getURI($this->_createRoute), 'GET', 'Create New Task');
    }

    protected function _displayEntry(TodoEntry $entry)
    {
        $editURI = $this->_router->getURI($this->_editRoute->getRouteWithReplacements(array('#id#' => $entry->getId())));
        $deleteURI = $this->_router->getURI($this->_deleteRoute->getRouteWithReplacements(array('#id#' => $entry->getId())));
        $completeURI = $this->_router->getURI($this->_completeRoute->getRouteWithReplacements(array('#id#' => $entry->getId())));

        if ($entry->getCompleted() > 0)
            $completeURI = null;

        $actions = array();
        if ($editURI != null)
            $actions[] = $this->_getActionButton($editURI, 'GET', 'Edit');
        if ($deleteURI != null)
            $actions[] = $this->_getActionButton($deleteURI, 'POST', 'Delete', 'Are you sure you wish to delete this task?');
        if ($completeURI != null)
            $actions[] = $this->_getActionButton($completeURI, 'POST', 'Complete', 'Are you sure you wish to complete this task?');

        echo '<div>';
            echo $entry->getTitle();
            echo implode(' | ', $actions);
            echo '<hr/>';
        echo '</div>';
    }

    protected function _getActionButton($uri, $method = 'POST', $caption, $confirmationMessage = null, $fields = array())
    {
        ob_start();
            $confirm = '';
            if ($confirmationMessage != null)
                $confirm = '"return confirm(\''.$confirmationMessage.'\');"';

            echo '<form method="'.$method.'" onclick='.$confirm.' action="'.$uri.'" class="actionForm">';
                foreach ($fields as $name => $value)
                    echo '<input type="hidden" name="'.$name.'" value="'.$value.'" />';

                echo '<input type="submit" value="'.$caption.'" />';
            echo '</form>';

        return ob_get_clean();
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $createRoute
     */
    public function setCreateRoute($createRoute)
    {
        $this->_createRoute = $createRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getCreateRoute()
    {
        return $this->_createRoute;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $completeRoute
     */
    public function setCompleteRoute($completeRoute)
    {
        $this->_completeRoute = $completeRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getCompleteRoute()
    {
        return $this->_completeRoute;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $deleteRoute
     */
    public function setDeleteRoute($deleteRoute)
    {
        $this->_deleteRoute = $deleteRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getDeleteRoute()
    {
        return $this->_deleteRoute;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $editRoute
     */
    public function setEditRoute($editRoute)
    {
        $this->_editRoute = $editRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getEditRoute()
    {
        return $this->_editRoute;
    }

    /**
     * @param \CannyDain\ShortyModules\Todo\Models\TodoEntry[] $entries
     */
    public function setEntries($entries)
    {
        $this->_entries = $entries;
    }

    /**
     * @return \CannyDain\ShortyModules\Todo\Models\TodoEntry
     */
    public function getEntries()
    {
        return $this->_entries;
    }
}