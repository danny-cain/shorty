<?php

namespace CannyDain\ShortyModules\Stories\DataLayer;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Shorty\DataAccess\ShortyDatasource;
use CannyDain\ShortyModules\Stories\Models\Chapter;
use CannyDain\ShortyModules\Stories\Models\Story;
use CannyDain\ShortyModules\Stories\StoriesModule;

class StoryDatalayer extends ShortyDatasource
{
    public function registerObjects()
    {
        $reader= new JSONFileDefinitionBuilder();
        $reader->readFile(dirname(__FILE__).'/datadictionary.json', $this->_datamapper);
    }

    /**
     * @param $storyID
     * @param $chapterNumber
     * @return Chapter
     */
    public function loadChapterByStoryAndNumber($storyID, $chapterNumber)
    {
        return array_shift($this->_datamapper->getObjectsWithCustomClauses(Chapter::OBJECT_NAME_CHAPTER, array
        (
            'number = :number',
            'story = :story',
        ), array
        (
            'number' => $chapterNumber,
            'story' => $storyID
        )));
    }

    /**
     * @param $storyID
     * @return Chapter
     */
    public function getLastChapterForStory($storyID)
    {
        return array_shift($this->_datamapper->getObjectsWithCustomClauses(Chapter::OBJECT_NAME_CHAPTER, array
        (
            'story = :story'
        ), array
        (
            'story' => $storyID
        ), 'number DESC', 0, 1));
    }

    /**
     * @param $id
     * @return Chapter
     */
    public function loadChapter($id)
    {
        return $this->_datamapper->loadObject(Chapter::OBJECT_NAME_CHAPTER, array('id' => $id));
    }

    /**
     * @param $storyID
     * @return Chapter[]
     */
    public function getChaptersByStory($storyID)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(Chapter::OBJECT_NAME_CHAPTER, array
        (
            'story = :story'
        ), array
        (
            'story' => $storyID
        ), 'number ASC');
    }

    /**
     * @param $id
     * @return Story
     */
    public function loadStory($id)
    {
        return $this->_datamapper->loadObject(Story::OBJECT_NAME_STORY, array('id' => $id));
    }

    /**
     * @param $authorID
     * @return Story[]
     */
    public function getStoriesByAuthor($authorID)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(Story::OBJECT_NAME_STORY, array
        (
            'author = :author'
        ), array
        (
            'author' => $authorID
        ), 'name ASC');
    }
}