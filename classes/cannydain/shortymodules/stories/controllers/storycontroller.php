<?php

namespace CannyDain\ShortyModules\Stories\Controllers;

use CannyDain\Lib\Execution\Exceptions\NotAuthorisedException;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\Controllers\ShortyModuleController;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\ShortyModules\Stories\Models\Chapter;
use CannyDain\ShortyModules\Stories\Models\Story;
use CannyDain\ShortyModules\Stories\StoriesModule;
use CannyDain\ShortyModules\Stories\Views\EditChapterView;
use CannyDain\ShortyModules\Stories\Views\EditStoryView;
use CannyDain\ShortyModules\Stories\Views\MyStoriesView;
use CannyDain\ShortyModules\Stories\Views\ReadStoryView;
use CannyDain\ShortyModules\Stories\Views\StoriesIndexView;

class StoryController extends ShortyModuleController implements  SessionConsumer
{
    /**
     * @var SessionHelper
     */
    protected $_session;

    public function DownloadStory($id)
    {

    }

    public function Read($storyID, $chapterNumber = 1)
    {
        $story = $this->_getModule()->getDatasource()->loadStory($storyID);
        $chapter = $this->_getModule()->getDatasource()->loadChapterByStoryAndNumber($storyID, $chapterNumber);

        $nextChapter = $this->_getModule()->getDatasource()->loadChapterByStoryAndNumber($storyID, $chapterNumber + 1);
        $previousChapter = $this->_getModule()->getDatasource()->loadChapterByStoryAndNumber($storyID, $chapterNumber - 1);
        $lastChapter = $this->_getModule()->getDatasource()->getLastChapterForStory($storyID);

        if ($story == null || $chapter == null)
            throw new \Exception("Unable to load Story/Chapter");

        $view = new ReadStoryView();
        $view->setStory($story);
        $view->setChapter($chapter);
        $view->setViewChapterRoute(new Route(__CLASS__, 'Read', array($storyID, '#chapter#')));

        if ($lastChapter != null)
            $view->setNumberOfChapters($lastChapter->getChapterNumber());

        if ($nextChapter != null)
            $view->setNextChapterRoute(new Route(__CLASS__, 'Read', array($storyID, $chapterNumber + 1)));

        if ($previousChapter != null)
            $view->setPreviousChapterRoute(new Route(__CLASS__, 'Read', array($storyID, $chapterNumber - 1)));

        return $view;
    }

    public function EditChapter($id)
    {
        $chapter = $this->_getModule()->getDatasource()->loadChapter($id);
        $story = $this->_getModule()->getDatasource()->loadStory($chapter->getStory());

        if ($story->getAuthor() != $this->_session->getUserID())
            throw new NotAuthorisedException();


        $view = new EditChapterView();
        $this->_dependencies->applyDependencies($view);
        $view->setChapter($chapter);
        $view->setSaveRoute(new Route(__CLASS__, 'EditChapter', array($id)));

        if ($view->updateFromPostAndReturnTrueIfPostedAndValid())
        {
            $view->getChapter()->save();
            $uri = $this->_router->getURI(new Route(__CLASS__, 'EditStory', array($story->getId())));

            return new RedirectView($uri);
        }

        return $view;
    }

    public function CreateChapter($storyID)
    {
        $story = $this->_getModule()->getDatasource()->loadStory($storyID);

        if ($story->getAuthor() != $this->_session->getUserID())
            throw new NotAuthorisedException();

        $chapter = new Chapter();
        $this->_dependencies->applyDependencies($chapter);
        $chapter->setStory($storyID);

        $lastChapter = $this->_getModule()->getDatasource()->getLastChapterForStory($storyID);

        if ($lastChapter != null)
            $chapterNumber = $lastChapter->getChapterNumber();
        else
            $chapterNumber = 0;

        $chapterNumber ++;
        $chapter->setChapterNumber($chapterNumber);

        $view = new EditChapterView();
        $this->_dependencies->applyDependencies($view);
        $view->setChapter($chapter);
        $view->setSaveRoute(new Route(__CLASS__, 'CreateChapter', array($storyID)));

        if ($view->updateFromPostAndReturnTrueIfPostedAndValid())
        {
            $view->getChapter()->save();
            $uri = $this->_router->getURI(new Route(__CLASS__, 'EditStory', array($story->getId())));

            return new RedirectView($uri);
        }

        return $view;
    }

    public function Index()
    {
        // index - display search, filter, mystories link
        $view = new StoriesIndexView();

        $view->setMyStoriesRoute(new Route(__CLASS__, 'MyStories'));

        return $view;
    }

    public function MyStories()
    {
        $view = new MyStoriesView();

        $view->setCreateRoute(new Route(__CLASS__, 'CreateStory'));
        $view->setEditRoute(new Route(__CLASS__, 'EditStory', array('#id#')));
        $view->setDownloadRoute(new Route(__CLASS__, 'DownloadStory', array('#id#')));
        $view->setStories($this->_getModule()->getDatasource()->getStoriesByAuthor($this->_session->getUserID()));

        return $view;
    }

    public function EditStory($id)
    {
        $story = $this->_getModule()->getDatasource()->loadStory($id);
        if ($story->getAuthor() != $this->_session->getUserID())
            throw new NotAuthorisedException();

        $view = new EditStoryView();
        $this->_dependencies->applyDependencies($view);

        $view->setStory($story);
        $view->setSaveRoute(new Route(__CLASS__, 'EditStory', array($id)));
        $view->setChapters($this->_getModule()->getDatasource()->getChaptersByStory($id));
        $view->setAddChapterRoute(new Route(__CLASS__, 'CreateChapter', array($id)));
        $view->setEditChapterRoute(new Route(__CLASS__, 'EditChapter', array('#id#')));

        if ($view->updateFromPostAndReturnTrueIfPostedAndValid())
        {
            $view->getStory()->save();

            return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'MyStories')));
        }

        return $view;
    }

    public function CreateStory()
    {
        $story = new Story();
        $this->_dependencies->applyDependencies($story);
        $story->setAuthor($this->_session->getUserID());

        $view = new EditStoryView();
        $this->_dependencies->applyDependencies($view);

        $view->setStory($story);
        $view->setSaveRoute(new Route(__CLASS__, 'CreateStory'));

        if ($view->updateFromPostAndReturnTrueIfPostedAndValid())
        {
            $view->getStory()->save();

            return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'MyStories')));
        }

        return $view;
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

    public function consumeSession(SessionHelper $session)
    {
        $this->_session = $session;
    }
}