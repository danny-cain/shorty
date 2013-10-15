<?php

namespace CannyDain\ShortyModules\Stories\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Views\ShortyView;
use CannyDain\ShortyModules\Stories\Models\Chapter;
use CannyDain\ShortyModules\Stories\Models\Story;

class ReadStoryView extends ShortyView
{
    /**
     * @var Story
     */
    protected $_story;

    /**
     * @var Chapter
     */
    protected $_chapter;

    /**
     * @var Route
     */
    protected $_viewChapterRoute;

    protected $_numberOfChapters;

    /**
     * @var Route
     */
    protected $_nextChapterRoute;

    /**
     * @var Route
     */
    protected $_previousChapterRoute;

    public function display()
    {
        echo '<h1>'.$this->_story->getName().'</h1>';

        $title = 'Chapter '.$this->_chapter->getChapterNumber().' '.$this->_chapter->getTitle();

        echo '<h2>'.$title.'</h2>';

        $this->_displayNavigationLinks();
        echo $this->_chapter->getContent();
        $this->_displayNavigationLinks();
    }

    protected function _displayNavigationLinks()
    {
        if ($this->_previousChapterRoute != null)
            $prevURI = $this->_router->getURI($this->_previousChapterRoute);
        else
            $prevURI = '';

        if ($this->_nextChapterRoute != null)
            $nextURI = $this->_router->getURI($this->_nextChapterRoute);
        else
            $nextURI = '';

        echo '<nav class="articleNavigation">';
            if ($prevURI != '')
                echo '<a href="'.$prevURI.'" class="previous">Previous Chapter</a>';

            $start =$this->_chapter->getChapterNumber() - 5;
            if ($start < 1)
                $start = 1;

            if ($start + 10 > $this->_numberOfChapters)
                $start = $this->_numberOfChapters - 10;

            if ($start < 1)
                $start = 1;

            $end = $start + 10;
            if ($end > $this->_numberOfChapters)
                $end = $this->_numberOfChapters;

            for ($i = $start; $i <= $end; $i ++)
            {
                if ($i == $this->_chapter->getChapterNumber())
                    echo '<a class="currentChapter">';
                else
                {
                    $uri = $this->_router->getURI($this->_viewChapterRoute->getRouteWithReplacements(array('#chapter#' => $i)));
                    echo '<a href="'.$uri.'">';
                }

                    echo $i;
                echo '</a>';
            }

            if ($nextURI != '')
                echo '<a href="'.$nextURI.'" class="next">Next Chapter</a>';

        echo '</nav>';
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $viewChapterRoute
     */
    public function setViewChapterRoute($viewChapterRoute)
    {
        $this->_viewChapterRoute = $viewChapterRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getViewChapterRoute()
    {
        return $this->_viewChapterRoute;
    }

    /**
     * @param \CannyDain\ShortyModules\Stories\Models\Chapter $chapter
     */
    public function setChapter($chapter)
    {
        $this->_chapter = $chapter;
    }

    /**
     * @return \CannyDain\ShortyModules\Stories\Models\Chapter
     */
    public function getChapter()
    {
        return $this->_chapter;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $nextChapterRoute
     */
    public function setNextChapterRoute($nextChapterRoute)
    {
        $this->_nextChapterRoute = $nextChapterRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getNextChapterRoute()
    {
        return $this->_nextChapterRoute;
    }

    public function setNumberOfChapters($numberOfChapters)
    {
        $this->_numberOfChapters = $numberOfChapters;
    }

    public function getNumberOfChapters()
    {
        return $this->_numberOfChapters;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $previousChapterRoute
     */
    public function setPreviousChapterRoute($previousChapterRoute)
    {
        $this->_previousChapterRoute = $previousChapterRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getPreviousChapterRoute()
    {
        return $this->_previousChapterRoute;
    }

    /**
     * @param \CannyDain\ShortyModules\Stories\Models\Story $story
     */
    public function setStory($story)
    {
        $this->_story = $story;
    }

    /**
     * @return \CannyDain\ShortyModules\Stories\Models\Story
     */
    public function getStory()
    {
        return $this->_story;
    }
}