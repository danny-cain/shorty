<?php

namespace CannyDain\ShortyModules\Stories\Controllers;

use CannyDain\Shorty\Controllers\ShortyModuleController;
use CannyDain\ShortyModules\Stories\StoriesModule;

class StoryController extends ShortyModuleController
{
    public function countChapters($storyID)
    {

    }

    public function getChapter($storyID, $chapterNumber)
    {

    }

    public function getStory($storyID)
    {

    }

    public function listStoriesByAuthor($author)
    {

    }

    /**
     * @return StoriesModule
     */
    protected function _getModule()
    {
        return parent::_getModule();
    }

    protected function _getModuleClassname()
    {
        return StoriesModule::STORY_MODULE_NAME;
    }
}