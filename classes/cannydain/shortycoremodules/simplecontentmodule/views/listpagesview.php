<?php

namespace CannyDain\ShortyCoreModules\SimpleContentModule\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\ShortyCoreModules\SimpleContentModule\Models\ContentPage;

class ListPagesView extends HTMLView
{
    /**
     * @var ContentPage[]
     */
    protected $_pages = array();

    protected $_editURITemplate = '';
    protected $_deleteURITemplate = '';
    protected $_createURI = '';

    public function display()
    {
        echo '<h1>Content Administration</h1>';

        echo '<table>';

            foreach ($this->_pages as $page)
                $this->_displayPage($page);

        echo '</table>';

        echo '<a class="itemActionButton" href="'.$this->_createURI.'">Create</a>';
    }

    protected function _displayPage(ContentPage $page)
    {
        $editURI = strtr($this->_editURITemplate, array('#id#' => $page->getFriendlyID()));
        $deleteURI = strtr($this->_deleteURITemplate, array('#id#' => $page->getFriendlyID()));

        echo '<tr>';
            echo '<th>'.$page->getTitle().'</th>';
            echo '<td>'.$page->getAuthorName().'</td>';
            echo '<td>';
                    echo '<a class="actionButton" href="'.$editURI.'">Edit</a>';
                    echo ' | ';
                    echo '<form class="actionForm" method="post" action="'.$deleteURI.'" onsubmit="return confirm(\'are you sure you wish to delete this page?\');">';
                        echo '<input type="submit" class="actionButton" value="Delete" />';
                    echo '</form>';
            echo '</td>';
        echo '</tr>';
    }

    public function setCreateURI($createURI)
    {
        $this->_createURI = $createURI;
    }

    public function getCreateURI()
    {
        return $this->_createURI;
    }

    public function setDeleteURITemplate($deleteURITemplate)
    {
        $this->_deleteURITemplate = $deleteURITemplate;
    }

    public function getDeleteURITemplate()
    {
        return $this->_deleteURITemplate;
    }

    public function setEditURITemplate($editURITemplate)
    {
        $this->_editURITemplate = $editURITemplate;
    }

    public function getEditURITemplate()
    {
        return $this->_editURITemplate;
    }

    public function setPages($pages)
    {
        $this->_pages = $pages;
    }

    public function getPages()
    {
        return $this->_pages;
    }
}