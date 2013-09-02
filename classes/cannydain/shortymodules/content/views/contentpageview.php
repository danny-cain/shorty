<?php

namespace CannyDain\ShortyModules\Content\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\Consumers\UserConsumer;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\Shorty\Helpers\UserHelper;
use CannyDain\ShortyModules\Content\Models\ContentPage;

class ContentPageView extends HTMLView implements SessionConsumer, UserConsumer
{
    /**
     * @var ContentPage
     */
    protected $_page;

    /**
     * @var SessionHelper
     */
    protected $_session;

    /**
     * @var UserHelper
     */
    protected $_users;

    public function display()
    {
        $authorName = $this->_users->getUsernameFromID($this->_page->getAuthor());

        echo '<h1>'.$this->_page->getTitle().'</h1>';
        echo '<div class="pageMeta">';
                echo 'Last modified by <span class="author">'.$authorName.'</span> on <span class="dateTime">'.date('Y-m-d H:i').'</span>';
        echo '</div>';

        echo $this->_page->getContent();
    }

    /**
     * @param \CannyDain\ShortyModules\Content\Models\ContentPage $page
     */
    public function setPage($page)
    {
        $this->_page = $page;
    }

    /**
     * @return \CannyDain\ShortyModules\Content\Models\ContentPage
     */
    public function getPage()
    {
        return $this->_page;
    }

    public function consumeSession(SessionHelper $session)
    {
        $this->_session = $session;
    }

    public function consumerUserHelper(UserHelper $helper)
    {
        $this->_users = $helper;
    }
}