<?php

namespace CannyDain\ShortyCoreModules\SimpleContentModule\Models;

class ContentPage
{
    protected $_id = 0;
    protected $_title = '';
    protected $_friendlyID = '';
    protected $_authorName = '';

    protected $_lastModified = 0;
    protected $_content = '';

    public function setAuthorName($authorName)
    {
        $this->_authorName = $authorName;
    }

    public function getAuthorName()
    {
        return $this->_authorName;
    }

    public function setContent($content)
    {
        $this->_content = $content;
    }

    public function getContent()
    {
        return $this->_content;
    }

    public function setFriendlyID($friendlyID)
    {
        $this->_friendlyID = $friendlyID;
    }

    public function getFriendlyID()
    {
        return $this->_friendlyID;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setLastModified($lastModified)
    {
        $this->_lastModified = $lastModified;
    }

    public function getLastModified()
    {
        return $this->_lastModified;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getTitle()
    {
        return $this->_title;
    }
}