<?php

namespace CannyDain\ShortyModules\Stories\Models;

use CannyDain\Shorty\Models\ShortyGUIDModel;

class Story extends ShortyGUIDModel
{
    const OBJECT_NAME_STORY = __CLASS__;

    const FIELD_NAME = 'name';
    const FIELD_AUTHOR = 'author';
    const FIELD_DESCRIPTION = 'description';

    protected $_name = '';
    protected $_author = 0;
    protected $_description = '';

    public function setAuthor($author)
    {
        $this->_author = $author;
    }

    public function getAuthor()
    {
        return $this->_author;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    protected function _getObjectTypeName()
    {
        return self::OBJECT_NAME_STORY;
    }

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        return array();
    }
}