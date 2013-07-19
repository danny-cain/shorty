<?php

namespace CannyDain\ShortyCoreModules\SimpleBlog\Views;

use CannyDain\Lib\CommentsManager\CommentsManager;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Utils\Date\DateFormatManager;
use CannyDain\Shorty\Consumers\CommentsConsumer;
use CannyDain\Shorty\Consumers\DateTimeConsumer;
use CannyDain\ShortyCoreModules\SimpleBlog\Models\Article;
use CannyDain\ShortyCoreModules\SimpleBlog\Models\Blog;

class ReadArticleView extends HTMLView implements CommentsConsumer, DateTimeConsumer
{
    /**
     * @var Blog
     */
    protected $_blog;

    /**
     * @var DateFormatManager
     */
    protected $_dates;

    /**
     * @var Article
     */
    protected $_article;

    /**
     * @var string
     */
    protected $_articleGUID;

    /**
     * @var CommentsManager
     */
    protected $_commentsManager;

    protected $_readBlogURI = '';
    protected $_readArticleURI = '';

    public function display()
    {
        echo '<h1>'.$this->_article->getTitle().'</h1>';
        echo '<div>Posted: '.$this->_dates->getFormattedDateTime($this->_article->getPosted()).' in <a href="'.$this->_readBlogURI.'">'.$this->_blog->getName().'</a></div>';
        echo '<div>Tags: '.implode(', ', $this->_article->getTags()).'</div>';
        echo '<div>';
            echo $this->_article->getContent();
        echo '</div>';

        $commentsView = $this->_commentsManager->getCommentsViewForObject($this->_articleGUID, $this->_readArticleURI);
        if ($commentsView != null)
        {
            echo '<div>';
                $commentsView->display();
            echo '</div>';
        }
    }

    public function setReadArticleURI($readArticleURI)
    {
        $this->_readArticleURI = $readArticleURI;
    }

    public function getReadArticleURI()
    {
        return $this->_readArticleURI;
    }

    /**
     * @param string $articleGUID
     */
    public function setArticleGUID($articleGUID)
    {
        $this->_articleGUID = $articleGUID;
    }

    /**
     * @return string
     */
    public function getArticleGUID()
    {
        return $this->_articleGUID;
    }

    /**
     * @param \CannyDain\ShortyCoreModules\SimpleBlog\Models\Article $article
     */
    public function setArticle($article)
    {
        $this->_article = $article;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\SimpleBlog\Models\Article
     */
    public function getArticle()
    {
        return $this->_article;
    }

    /**
     * @param \CannyDain\ShortyCoreModules\SimpleBlog\Models\Blog $blog
     */
    public function setBlog($blog)
    {
        $this->_blog = $blog;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\SimpleBlog\Models\Blog
     */
    public function getBlog()
    {
        return $this->_blog;
    }

    public function setReadBlogURI($readBlogURI)
    {
        $this->_readBlogURI = $readBlogURI;
    }

    public function getReadBlogURI()
    {
        return $this->_readBlogURI;
    }

    public function consumeCommentsManager(CommentsManager $manager)
    {
        $this->_commentsManager = $manager;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDateTimeManager(DateFormatManager $dependency)
    {
        $this->_dates = $dependency;
    }
}