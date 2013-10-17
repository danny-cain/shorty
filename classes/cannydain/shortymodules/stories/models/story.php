<?php

namespace CannyDain\ShortyModules\Stories\Models;

use CannyDain\Shorty\Models\ShortyGUIDModel;

class Story extends ShortyGUIDModel
{
    const OBJECT_NAME_STORY = __CLASS__;

    const FIELD_NAME = 'name';
    const FIELD_AUTHOR = 'author';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_FRONT_COVER = 'front-cover';
    const FIELD_REAR_COVER = 'rear-cover';

    protected $_name = '';
    protected $_author = 0;
    protected $_description = '';
    protected $_frontCoverImage = '';
    protected $_rearCoverImage = '';

    public function setFrontCoverImage($frontCoverImage)
    {
        $this->_frontCoverImage = $frontCoverImage;
    }

    public function getFrontCoverImage()
    {
        return $this->_frontCoverImage;
    }

    public function setRearCoverImage($rearCoverImage)
    {
        $this->_rearCoverImage = $rearCoverImage;
    }

    public function getRearCoverImage()
    {
        return $this->_rearCoverImage;
    }

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