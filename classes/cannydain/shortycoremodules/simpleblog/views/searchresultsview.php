<?php

namespace CannyDain\ShortyCoreModules\SimpleBlog\Views;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Utils\Date\DateFormatManager;
use CannyDain\Shorty\Consumers\DateTimeConsumer;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\ShortyCoreModules\SimpleBlog\DataAccess\SimpleBlogDatasource;
use CannyDain\ShortyCoreModules\SimpleBlog\Models\Article;
use CannyDain\ShortyCoreModules\SimpleBlog\Models\Blog;

class SearchResultsView extends HTMLView implements DependencyConsumer, DateTimeConsumer
{
    // #article#, #blog#
    protected $_viewArticleURITemplate = '';

    /**
     * @var DateFormatManager
     */
    protected $_dateTimes;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;
    /**
     * @var Article[]
     */
    protected $_articles = array();

    public function display()
    {
        foreach ($this->_articles as $article)
            $this->_displayArticle($article);
    }

    protected function _displayArticle(Article $article)
    {
        $blog = $this->_getBlog($article->getBlog());

        $readURI = strtr($this->_viewArticleURITemplate, array('#blog#' => $blog->getUri(), '#article#' => $article->getUri()));
        echo '<div>';
            echo '<h2><a href="'.$readURI.'">'.$article->getTitle().'</a></h2>';
            echo '<div>Posted: '.$this->_dateTimes->getFormattedDateTime($article->getPosted()).'</div>';
            echo '<div>Tags: '.implode(', ', $article->getTags()).'</div>';
        echo '</div>';
    }

    /**
     * @param $id
     * @return Blog
     */
    protected function _getBlog($id)
    {
        static $cache = array();

        if (!isset($cache[$id]))
        {
            $cache[$id] = $this->datasource()->getBlog($id);
        }

        return $cache[$id];
    }
    protected function datasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new SimpleBlogDatasource();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function setArticles($articles)
    {
        $this->_articles = $articles;
    }

    public function getArticles()
    {
        return $this->_articles;
    }

    public function setViewArticleURITemplate($viewArticleURITemplate)
    {
        $this->_viewArticleURITemplate = $viewArticleURITemplate;
    }

    public function getViewArticleURITemplate()
    {
        return $this->_viewArticleURITemplate;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeDateTimeManager(DateFormatManager $dependency)
    {
        $this->_dateTimes = $dependency;
    }
}