<?php

namespace CannyDain\ShortyCoreModules\SimpleContentModule\Controllers;

use CannyDain\Lib\CommentsManager\CommentsManager;
use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\ViewFactory;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\CommentsConsumer;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Consumers\URIManagerConsumer;
use CannyDain\Shorty\Consumers\UserControlConsumer;
use CannyDain\Shorty\Consumers\ViewFactoryConsumer;
use CannyDain\Shorty\Routing\URIManager;
use CannyDain\Shorty\UserControl\UserControl;
use CannyDain\ShortyCoreModules\SimpleContentModule\DataAccess\SimpleContentDataAccess;
use CannyDain\ShortyCoreModules\SimpleContentModule\Models\ContentPage;
use CannyDain\ShortyCoreModules\SimpleContentModule\Views\EditPageView;
use CannyDain\ShortyCoreModules\SimpleContentModule\Views\ListPagesView;
use Exception;

class ContentAdminController implements ControllerInterface, RouterConsumer, UserControlConsumer, RequestConsumer, DependencyConsumer, ViewFactoryConsumer, CommentsConsumer, URIManagerConsumer
{
    const CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var CommentsManager
     */
    protected $_commentsManager;

    /**
     * @var URIManager
     */
    protected $_uriManager;

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var ViewFactory
     */
    protected $_viewFactory;

    /**
     * @var UserControl
     */
    protected $_userControl;

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    public function Index()
    {
        return $this->ListPages();
    }

    protected function _ensureUserIsAdmin()
    {
        $userID = $this->_userControl->getCurrentUserID();
        if (!$this->_userControl->isAdministrator($userID))
            throw new Exception("Not Allowed");
    }

    protected function _editPageViewFactory($pageID = null)
    {
        /**
         * @var EditPageView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\SimpleContentModule\\Views\\EditPageView');
        $this->_dependencies->applyDependencies($view);

        if ($pageID == null)
            $view->setSaveURI($this->_router->getURI(new Route(__CLASS__, 'CreatePage')));
        else
        {
            $page = $this->datasource()->getPageByFriendlyID($pageID);
            if ($page == null)
                $page = $this->datasource()->getPageByID($pageID);

            $view->setSaveURI($this->_router->getURI(new Route(__CLASS__, 'EditPage', array($pageID))));
            $view->setCommentsAdminView($this->_commentsManager->getAdministrateCommentsView($this->datasource()->getPageGUID($page->getId()), $this->_router->getURI(new Route(__CLASS__, 'EditPage', array($pageID)))));
            $view->setPage($page);
        }

        return $view;
    }

    protected function _listPagesViewFactory()
    {
        /**
         * @var ListPagesView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\SimpleContentModule\\Views\\ListPagesView');
        $this->_dependencies->applyDependencies($view);

        $view->setCreateURI($this->_router->getURI(new Route(__CLASS__, 'CreatePage')));
        $view->setEditURITemplate($this->_router->getURI(new Route(__CLASS__, 'EditPage', array('#id#'))));
        $view->setDeleteURITemplate($this->_router->getURI(new Route(__CLASS__, 'DeletePage', array('#id#'))));

        return $view;
    }

    protected function _getPageByID($id)
    {
        $page = $this->datasource()->getPageByFriendlyID($id);
        if ($page == null)
            $page = $this->datasource()->getPageByID($id);

        return $page;
    }

    protected function datasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new SimpleContentDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function EditPage($pageID)
    {
        $this->_ensureUserIsAdmin();
        $page = $this->_getPageByID($pageID);
        $view = $this->_editPageViewFactory($pageID);

        $uriWidget = $this->_uriManager->getAssignURIWidgetForRoute(new Route(ContentController::CONTROLLER_CLASS_NAME, 'View',array($view->getPage()->getFriendlyID())));
        $view->addWidget($uriWidget);

        $view->setPage($page);

        if ($this->_request->isPost())
        {
            $page->setLastModified(time());
            $view->updateModel($this->_request);
            $this->datasource()->savePage($page);
            $uriWidget->updateAndSaveFromRequest($this->_request);

            return $this->_getPRGRedirect($this->_router->getURI(new Route(__CLASS__)));
        }

        return $view;
    }

    protected function _getPRGRedirect($uri)
    {
        $view = new RedirectView();
        $this->_dependencies->applyDependencies($view);

        $view->setResponseCode(RedirectView::RESPONSE_CODE_TEMPORARY_REDIRECT);
        $view->setUri($uri);

        return $view;
    }

    public function DeletePage($pageID)
    {
        $page = $this->datasource()->getPageByFriendlyID($pageID);
        if ($page == null)
            $page = $this->datasource()->getPageByID($pageID);

        $this->_ensureUserIsAdmin();
        if ($this->_request->isPost())
            $this->datasource()->deletePage($page->getId());

        return $this->_getPRGRedirect($this->_router->getURI(new Route(__CLASS__)));
    }

    public function CreatePage()
    {
        $this->_ensureUserIsAdmin();
        $page = new ContentPage();
        $view = $this->_editPageViewFactory(null);
        $view->setPage($page);

        if ($this->_request->isPost())
        {
            $page->setLastModified(time());
            $view->updateModel($this->_request);
            $this->datasource()->savePage($page);

            return $this->_getPRGRedirect($this->_router->getURI(new Route(__CLASS__)));
        }

        return $view;
    }

    public function ListPages()
    {
        $this->_ensureUserIsAdmin();
        $view = $this->_listPagesViewFactory();
        $view->setPages($this->datasource()->getAllPages());

        return $view;
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

    public function consumeUserController(UserControl $dependency)
    {
        $this->_userControl = $dependency;
    }

    public function consumeViewFactory(ViewFactory $dependency)
    {
        $this->_viewFactory = $dependency;
    }

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return true;
    }

    public function consumeCommentsManager(CommentsManager $manager)
    {
        $this->_commentsManager = $manager;
    }

    public function consumeURIManager(URIManager $dependency)
    {
        $this->_uriManager = $dependency;
    }
}