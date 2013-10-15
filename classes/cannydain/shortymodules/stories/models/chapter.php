<?php

namespace CannyDain\ShortyModules\Stories\Models;

use CannyDain\Shorty\Models\ShortyGUIDModel;

class Chapter extends ShortyGUIDModel
{
    const OBJECT_NAME_CHAPTER = __CLASS__;

    const FIELD_NUMBER = 'number';
    const FIELD_TITLE = 'title';
    const FIELD_CONTENT = 'content';

    protected $_story = 0;
    protected $_chapterNumber = 0;
    protected $_title = '';
    protected $_content = '';

    public function setChapterNumber($chapterNumber)
    {
        $this->_chapterNumber = $chapterNumber;
    }

    public function getChapterNumber()
    {
        return $this->_chapterNumber;
    }

    public function setContent($content)
    {
        $this->_content = $content;
    }

    public function getContent()
    {
        return $this->_content;
    }

    public function setStory($story)
    {
        $this->_story = $story;
    }

    public function getStory()
    {
        return $this->_story;
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
        return self::OBJECT_NAME_CHAPTER;
    }

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        return array();
    }
}