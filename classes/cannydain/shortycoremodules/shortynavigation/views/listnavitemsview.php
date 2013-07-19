<?php

namespace CannyDain\ShortyCoreModules\ShortyNavigation\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\ShortyCoreModules\ShortyNavigation\Models\NavItemModel;

class ListNavItemsView extends HTMLView
{
    /**
     * @var NavItemModel[]
     */
    protected $_items;
    protected $_viewChildrenLinkTemplate = '';
    protected $_editLinkTemplate = '';
    protected $_createLink = '';
    protected $_deleteLinkTemplate = '';

    protected $_upOneLevelLink = '';

    public function display()
    {
        $this->_displayBreadcrumbs();
        echo '<h1>Site Menu Administration</h1>';

        foreach ($this->_items as $item)
            $this->_displayItem($item);

        echo '<a href="'.$this->_createLink.'">[Create New Nav Item]</a>';
    }

    protected function _displayItem(NavItemModel $item)
    {
        $editLink = strtr($this->_editLinkTemplate, array('#id#' => $item->getId()));
        $deleteLink = strtr($this->_deleteLinkTemplate, array('#id#' => $item->getId()));
        $childrenLink = strtr($this->_viewChildrenLinkTemplate, array('#id#' => $item->getId()));

        echo '<div>';
            echo '<div style="display: inline-block; width: 200px;">';
                if ($item->getCaption() == '')
                    echo '- Untitled -';
                else
                    echo $item->getCaption();
            echo '</div>';

            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            echo '<a class="actionButton" href="'.$childrenLink.'">View Children</a>';
            echo ' | ';
            echo '<a class="actionButton" href="'.$editLink.'">Edit</a>';
            echo ' | ';
            echo '<form method="post" onsubmit="return confirm(\'Are you sure you wish to delete this item?\');" action="'.$deleteLink.'" class="actionForm">';
                echo '<input type="submit" value="Delete" class="actionButton" />';
            echo '</form>';
        echo '</div>';
    }

    public function setDeleteLinkTemplate($deleteLinkTemplate)
    {
        $this->_deleteLinkTemplate = $deleteLinkTemplate;
    }

    public function getDeleteLinkTemplate()
    {
        return $this->_deleteLinkTemplate;
    }

    public function setCreateLink($createLink)
    {
        $this->_createLink = $createLink;
    }

    public function getCreateLink()
    {
        return $this->_createLink;
    }

    public function setEditLinkTemplate($editLinkTemplate)
    {
        $this->_editLinkTemplate = $editLinkTemplate;
    }

    public function getEditLinkTemplate()
    {
        return $this->_editLinkTemplate;
    }

    public function setItems($items)
    {
        $this->_items = $items;
    }

    public function getItems()
    {
        return $this->_items;
    }

    public function setUpOneLevelLink($upOneLevelLink)
    {
        $this->_upOneLevelLink = $upOneLevelLink;
    }

    public function getUpOneLevelLink()
    {
        return $this->_upOneLevelLink;
    }

    public function setViewChildrenLinkTemplate($viewChildrenLinkTemplate)
    {
        $this->_viewChildrenLinkTemplate = $viewChildrenLinkTemplate;
    }

    public function getViewChildrenLinkTemplate()
    {
        return $this->_viewChildrenLinkTemplate;
    }
}