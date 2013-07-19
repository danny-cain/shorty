<?php

namespace CannyDain\ShortyCoreModules\SimpleBlog\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\ViewFactory;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Consumers\UserControlConsumer;
use CannyDain\Shorty\Consumers\ViewFactoryConsumer;
use CannyDain\Shorty\UserControl\UserControl;
use CannyDain\ShortyCoreModules\SimpleBlog\DataAccess\SimpleBlogDatasource;
use CannyDain\ShortyCoreModules\SimpleBlog\Models\Article;
use CannyDain\ShortyCoreModules\SimpleBlog\Models\Blog;
use CannyDain\ShortyCoreModules\SimpleBlog\Views\PostArticleView;
use CannyDain\ShortyCoreModules\SimpleBlog\Views\ReadArticleView;
use CannyDain\ShortyCoreModules\SimpleBlog\Views\ReadBlogView;
use CannyDain\ShortyCoreModules\SimpleBlog\Views\SearchBlogView;
use CannyDain\ShortyCoreModules\SimpleBlog\Views\SearchResultsView;

class SimpleBlogController implements ControllerInterface, RouterConsumer, DependencyConsumer, RequestConsumer, ViewFactoryConsumer, UserControlConsumer
{
    /**
     * @var ViewFactory
     */
    protected $_viewFactory;

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var UserControl
     */
    protected $_session;

    public function Search($blog = null)
    {
        $view = $this->_viewFactory_SearchView($blog);
        $view->updateModelFromRequest($this->_request);

        if ($view->wasSearchRequested())
        {
            $resultsView = $this->_viewFactory_SearchResultsView();
            $resultsView->setArticles($this->datasource()->searchArticles($view->getSearchTerm(), $blog));
            $view->setSearchResults($resultsView);
        }

        return $view;
    }

    public function View($blogID, $articleIDOrURI = '')
    {
        if ($articleIDOrURI == '')
            return $this->_viewBlog($blogID);
        else
            return $this->_viewArticle($blogID, $articleIDOrURI);
    }

    public function Post($blogID)
    {
        $userID = $this->_session->getCurrentUserID();
        $blog = $this->datasource()->getBlogByURI($blogID);
        if ($blog == null)
            $blog = $this->datasource()->getBlog($blogID);

        if ($blog->getOwner() != $userID)
            throw new \Exception("Unauthorised");

        $article = new Article();
        $article->setPosted(time());
        $article->setBlog($blog->getId());
        $view = $this->_viewFactory_PostArticle($blog, $article);

        if ($this->_request->isPost())
        {
            $view->updateModelFromPost($this->_request);
            $this->datasource()->saveArticle($article);
            return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'View', array($blog->getUri(), $article->getUri()))));
        }

        return $view;
    }

    protected function _viewBlog($blogID)
    {
        $blog = $this->datasource()->getBlogByURI($blogID);
        if ($blog == null)
            $blog = $this->datasource()->getBlog($blogID);

        $articles = $this->datasource()->getMostRecentArticlesForBlog($blog->getId(), 10);

        $view = $this->_viewFactory_ReadBlog($blog, $articles);
        $searchView = $this->_viewFactory_SearchView($blog->getId());
        $view->setSearchView($searchView);

        return $view;
    }

    protected function _viewArticle($blogID, $idOrURI)
    {
        $blog = $this->datasource()->getBlogByURI($blogID);
        if ($blog == null)
            $blog = $this->datasource()->getBlog($blogID);

        $article = $this->datasource()->getArticleByURI($blog->getId(), $idOrURI);
        if ($article === null)
            $article = $this->datasource()->getArticleByID($idOrURI);

        return $this->_viewFactory_ReadArticle($blog, $article);
    }

    protected function _viewFactory_SearchResultsView()
    {
        /**
         * @var SearchResultsView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\SimpleBlog\\Views\\SearchResultsView');

        $view->setViewArticleURITemplate($this->_router->getURI(new Route(__CLASS__, 'View', array('#blog#', '#article#'))));

        return $view;
    }

    protected function _viewFactory_SearchView($blogID = null)
    {
        /**
         * @var SearchBlogView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\SimpleBlog\\Views\\SearchBlogView');

        if ($blogID == null)
            $view->setSearchURI($this->_router->getURI(new Route(__CLASS__, 'Search')));
        else
            $view->setSearchURI($this->_router->getURI(new Route(__CLASS__, 'Search', array($blogID))));

        return $view;
    }

    protected function _viewFactory_PostArticle(Blog $blog, Article $article)
    {
        /**
         * @var PostArticleView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\SimpleBlog\\Views\\PostArticleView');

        $view->setArticle($article);
        $view->setBlog($blog);
        $view->setSaveURI($this->_router->getURI(new Route(__CLASS__, 'Post', array($blog->getUri()))));

        return $view;
    }

    /**
     * @param Blog $blog
     * @param Article $article
     * @return ReadArticleView
     */
    protected function _viewFactory_ReadArticle(Blog $blog, Article $article)
    {
        /**
         * @var ReadArticleView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\SimpleBlog\\Views\\ReadArticleView');

        $this->_dependencies->applyDependencies($view);
        $view->setArticle($article);
        $view->setBlog($blog);
        $view->setReadBlogURI($this->_router->getURI(new Route(__CLASS__, 'View', array($blog->getUri()))));
        $view->setReadArticleURI($this->_router->getURI(new Route(__CLASS__, 'View', array($blog->getUri(), $article->getUri()))));
        $view->setArticleGUID($this->datasource()->getArticleGUID($article->getId()));

        return $view;
    }

    /**
     * @param Blog $blog
     * @param Article[] $articles
     * @return \CannyDain\ShortyCoreModules\SimpleBlog\Views\ReadBlogView
     */
    protected function _viewFactory_ReadBlog(Blog $blog, $articles)
    {
        /**
         * @var ReadBlogView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\SimpleBlog\\Views\\ReadBlogView');

        $this->_dependencies->applyDependencies($view);
        $view->setArticles($articles);
        $view->setBlog($blog);
        $view->setReadArticleURITemplate($this->_router->getURI(new Route(__CLASS__, 'View', array($blog->getUri(), '#id#'))));

        if ($blog->getOwner() == $this->_session->getCurrentUserID())
            $view->setPostArticleURI($this->_router->getURI(new Route(__CLASS__, 'Post', array($blog->getUri()))));

        return $view;
    }

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return false;
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

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }

    public function consumeViewFactory(ViewFactory $dependency)
    {
        $this->_viewFactory = $dependency;
    }

    public function consumeUserController(UserControl $dependency)
    {
        $this->_session = $dependency;
    }
}