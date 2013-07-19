<?php

namespace CannyDain\ShortyCoreModules\SimpleContentModule\Views;

use CannyDain\Lib\CommentsManager\CommentsManager;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Utils\Date\DateFormatManager;
use CannyDain\Shorty\Consumers\CommentsConsumer;
use CannyDain\Shorty\Consumers\DateTimeConsumer;
use CannyDain\ShortyCoreModules\SimpleContentModule\Models\ContentPage;

class ContentPageView extends HTMLView implements CommentsConsumer, DateTimeConsumer
{
    /**
     * @var ContentPage
     */
    protected $_page;

    protected $_pageGUID = '';

    protected $_viewURI = '';

    /**
     * @var DateFormatManager
     */
    protected $_dateTime;

    /**
     * @var CommentsManager
     */
    protected $_comments;

    public function __construct(ContentPage $page, $pageGUID, $viewURI)
    {
        $this->_page = $page;
        $this->_pageGUID = $pageGUID;
        $this->_viewURI = $viewURI;
    }

    public function display()
    {
        echo '<div class="funkySimpleContentPage">';
            echo '<div class="pageMeta">';
                echo '<h1>'.$this->_page->getTitle().'</h1>';
                echo '<div class="author">';
                    echo 'Last Modified By '.$this->_page->getAuthorName().' on '.$this->_dateTime->getFormattedDateTime($this->_page->getLastModified());
                echo '</div>';
            echo '</div>';

            echo '<div class="pageContent">';
                echo $this->_page->getContent();
            echo '</div>';

            $this->_displayComments();
        echo '</div>';
    }

    protected function _displayComments()
    {
        echo '<div class="commentCount">';
            echo $this->_comments->getCommentCountForObject($this->_pageGUID).' comment(s)';
        echo '</div>';

        $view = $this->_comments->getCommentsViewForObject($this->_pageGUID, $this->_viewURI);
        if ($view != null)
            $view->display();
    }

    public function consumeCommentsManager(CommentsManager $manager)
    {
        $this->_comments = $manager;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDateTimeManager(DateFormatManager $dependency)
    {
        $this->_dateTime = $dependency;
    }
}