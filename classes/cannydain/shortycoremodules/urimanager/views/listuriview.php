<?php

namespace CannyDain\ShortyCoreModules\URIManager\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\ShortyCoreModules\URIManager\Models\URIMappingModel;

class ListURIView extends HTMLView
{
    protected $_pageNum = 1;
    protected $_noPages = 0;
    protected $_paginationLinkTemplate = '';

    protected $_editURITemplate = '';
    protected $_deleteURITemplate = '';
    protected $_createURI = '';

    /**
     * @var URIMappingModel[]
     */
    protected $_uris = array();

    public function display()
    {
        echo '<h1>URI Administration</h1>';

        $this->_displayPagination();
        foreach ($this->_uris as $uri)
            $this->_displayURI($uri);

        $this->_displayPagination();

        echo '<div>';
            echo '<a href="'.$this->_createURI.'">[ create new URI ]</a>';
        echo '</div>';
    }

    protected function _displayURI(URIMappingModel $uri)
    {
        $editURI = strtr($this->_editURITemplate, array('#id#' => $uri->getId()));
        $deleteURI = strtr($this->_deleteURITemplate, array('#id#' => $uri->getId()));

        echo '<div>';
            echo '<div style="display: inline-block; vertical-align: top; width: 25%;">';
                echo $uri->getUri();
            echo '</div>';

            echo '<div style="display: inline-block; vertical-align: top; width: 25%;">';
                echo '<a class="actionButton" href="'.$editURI.'">Edit</a>';
                echo '<form action="'.$deleteURI.'" method="post" class="actionForm" onsubmit="return confirm(\'Are you sure\');">';
                    echo '<input type="submit" class="actionButton" value="Delete" />';
                echo '</form>';
            echo '</div>';
        echo '</div>';
    }

    protected function _displayPagination()
    {
        $links = array();
        if ($this->_pageNum > 1)
            $links[] = '<a href="'.strtr($this->_paginationLinkTemplate, array('#page#' => $this->_pageNum - 1)).'">[previous]</a>';

        if ($this->_pageNum < $this->_noPages)
            $links[] = '<a href="'.strtr($this->_paginationLinkTemplate, array('#page#' => $this->_pageNum + 1)).'">[next]</a>';

        echo '<div>';
            echo implode(' | ', $links);
        echo '</div>';
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

    public function setNoPages($noPages)
    {
        $this->_noPages = $noPages;
    }

    public function getNoPages()
    {
        return $this->_noPages;
    }

    public function setPageNum($pageNum)
    {
        $this->_pageNum = $pageNum;
    }

    public function getPageNum()
    {
        return $this->_pageNum;
    }

    public function setPaginationLinkTemplate($paginationLinkTemplate)
    {
        $this->_paginationLinkTemplate = $paginationLinkTemplate;
    }

    public function getPaginationLinkTemplate()
    {
        return $this->_paginationLinkTemplate;
    }

    public function setUris($uris)
    {
        $this->_uris = $uris;
    }

    public function getUris()
    {
        return $this->_uris;
    }
}