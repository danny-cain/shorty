<?php

namespace CannyDain\ShortyModules\Stories\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Views\ShortyView;
use CannyDain\ShortyModules\Stories\Models\Story;

class MyStoriesView extends ShortyView
{
    /**
     * @var Story[]
     */
    protected $_stories;

    /**
     * @var Route
     */
    protected $_editRoute;

    /**
     * @var Route
     */
    protected $_createRoute;

    /**
     * @var Route
     */
    protected $_downloadRoute;

    public function display()
    {
        echo '<h1>My Stories</h1>';

        $this->_displayButtonPane();
            $this->_displayStories();
        $this->_displayButtonPane();
    }

    protected function _displayStories()
    {
        if (count($this->_stories) == 0)
        {
            echo '<p><em>';
                echo 'You have no stories';
            echo '</em></p>';
        }
        foreach ($this->_stories as $story)
            $this->_displayStory($story);
    }

    protected function _displayStory(Story $story)
    {
        $editURI = $this->_router->getURI($this->_editRoute->getRouteWithReplacements(array('#id#' => $story->getId())));
        $downloadURI = $this->_router->getURI($this->_downloadRoute->getRouteWithReplacements(array('#id#' => $story->getId())));

        echo '<div style="margin: 10px; ">';
            echo '<div style="text-align: center; display: inline-block; vertical-align: top;">';
                echo '<a href="'.$editURI.'" class="button">Edit</a>';
                echo '<a href="'.$downloadURI.'" class="button">Download</a>';
            echo '</div>';

            echo '<div style="display: inline-block; vertical-align: top; width: 20%;">';
                echo $story->getName();
            echo '</div>';
        echo '</div>';
    }

    protected function _displayButtonPane()
    {
        echo '<div class="buttonPane">';
            echo '<a href="'.$this->_router->getURI($this->_createRoute).'" class="button">New Story</a>';
        echo '</div>';
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $downloadRoute
     */
    public function setDownloadRoute($downloadRoute)
    {
        $this->_downloadRoute = $downloadRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getDownloadRoute()
    {
        return $this->_downloadRoute;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $createRoute
     */
    public function setCreateRoute($createRoute)
    {
        $this->_createRoute = $createRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getCreateRoute()
    {
        return $this->_createRoute;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $editRoute
     */
    public function setEditRoute($editRoute)
    {
        $this->_editRoute = $editRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getEditRoute()
    {
        return $this->_editRoute;
    }

    public function setStories($stories)
    {
        $this->_stories = $stories;
    }

    public function getStories()
    {
        return $this->_stories;
    }
}