<?php

namespace CannyDain\ShortyCoreModules\SimpleBlog\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\TitledView;
use CannyDain\Lib\Utils\Date\DateFormatManager;
use CannyDain\Shorty\Consumers\DateTimeConsumer;
use CannyDain\ShortyCoreModules\SimpleBlog\Models\Article;
use CannyDain\ShortyCoreModules\SimpleBlog\Models\Blog;

class ReadBlogView extends HTMLView implements DateTimeConsumer, TitledView
{
    /**
     * @var Blog
     */
    protected $_blog;

    /**
     * @var DateFormatManager
     */
    protected $_dateManager;

    /**
     * @var Article[]
     */
    protected $_articles;

    protected $_postArticleURI = '';

    protected $_readArticleURITemplate;

    /**
     * @var SearchBlogView
     */
    protected $_searchView;

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Blogs - '.$this->_blog->getName();
    }

    public function display()
    {
        echo '<h1>'.$this->_blog->getName().'</h1>';
        echo '<div style="font-style: italic; ">';
            echo $this->_blog->getTagline();
        echo '</div>';

        if ($this->_searchView !== null)
            $this->_searchView->display();

        if ($this->_postArticleURI != '')
        {
            echo '<div>';
                echo '<a href="'.$this->_postArticleURI.'">[New Post]</a>';
            echo '</div>';
        }

        foreach ($this->_articles as $article)
            $this->_displayArticle($article);
    }

    protected function _displayArticle(Article $article)
    {
        $readURI = strtr($this->_readArticleURITemplate, array('#id#' => $article->getUri()));
        echo '<div>';
            echo '<h2><a href="'.$readURI.'">'.$article->getTitle().'</a></h2>';
            echo '<div>Posted: '.$this->_dateManager->getFormattedDateTime($article->getPosted()).'</div>';
            echo '<div>Tags: '.implode(', ', $article->getTags()).'</div>';
        echo '</div>';
    }

    /**
     * @param \CannyDain\ShortyCoreModules\SimpleBlog\Views\SearchBlogView $searchView
     */
    public function setSearchView($searchView)
    {
        $this->_searchView = $searchView;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\SimpleBlog\Views\SearchBlogView
     */
    public function getSearchView()
    {
        return $this->_searchView;
    }

    public function setPostArticleURI($postArticleURI)
    {
        $this->_postArticleURI = $postArticleURI;
    }

    public function getPostArticleURI()
    {
        return $this->_postArticleURI;
    }

    public function setArticles($articles)
    {
        $this->_articles = $articles;
    }

    public function getArticles()
    {
        return $this->_articles;
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

    public function setReadArticleURITemplate($readArticleURITemplate)
    {
        $this->_readArticleURITemplate = $readArticleURITemplate;
    }

    public function getReadArticleURITemplate()
    {
        return $this->_readArticleURITemplate;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDateTimeManager(DateFormatManager $dependency)
    {
        $this->_dateManager = $dependency;
    }
}