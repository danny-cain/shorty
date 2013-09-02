<?php

namespace CannyDain\ShortyModules\Content\Models;

use CannyDain\Shorty\Models\ShortyGUIDModel;

class ContentPage extends ShortyGUIDModel
{
    const TYPE_NAME_CONTENT_PAGE = __CLASS__;

    const FIELD_TITLE = 'title';
    const FIELD_CONTENT = 'content';
    const FIELD_AUTHOR = 'author';
    const FIELD_LAST_MODIFIED = 'lastmodified';
    const FIELD_CREATED = 'created';

    protected $_title = '';
    protected $_content = '';
    protected $_author = 0;
    protected $_lastModified = 0;
    protected $_created = 0;

    public function setAuthor($author)
    {
        $this->_author = $author;
    }

    public function getAuthor()
    {
        return $this->_author;
    }

    public function setContent($content)
    {
        $this->_content = $content;
    }

    public function getContent()
    {
        return $this->_content;
    }

    public function setCreated($created)
    {
        $this->_created = $created;
    }

    public function getCreated()
    {
        return $this->_created;
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

    protected function _getObjectTypeName()
    {
        return self::TYPE_NAME_CONTENT_PAGE;
    }

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        return array();
    }
}