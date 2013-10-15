<?php

namespace CannyDain\ShortyModules\Stories\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\Shorty\Views\ShortyView;

class StoriesIndexView extends ShortyView implements SessionConsumer
{
    /**
     * @var SessionHelper
     */
    protected $_session;

    /**
     * @var Route
     */
    protected $_myStoriesRoute;

    public function display()
    {
        echo '<h1>Stories</h1>';

        $this->_displayMyStoriesButton();
    }

    protected function _displayMyStoriesButton()
    {
        if ($this->_session->getUserID() < 1)
            return;
        if ($this->_myStoriesRoute == null)
            return;

        echo '<a class="button" href="'.$this->_router->getURI($this->_myStoriesRoute).'">';
            echo 'My Stories';
        echo '</a>';
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $myStoriesRoute
     */
    public function setMyStoriesRoute($myStoriesRoute)
    {
        $this->_myStoriesRoute = $myStoriesRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getMyStoriesRoute()
    {
        return $this->_myStoriesRoute;
    }

    public function consumeSession(SessionHelper $session)
    {
        $this->_session = $session;
    }
}