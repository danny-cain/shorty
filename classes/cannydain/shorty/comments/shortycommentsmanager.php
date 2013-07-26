<?php

namespace CannyDain\Shorty\Comments;

use CannyDain\Lib\CommentsManager\CommentsManager;
use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\ViewFactory;
use CannyDain\Lib\UI\Views\NullHTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\Comments\Controllers\ShortyCommentsController;
use CannyDain\Shorty\Comments\Datasource\ShortyCommentsDatasource;
use CannyDain\Shorty\Comments\Models\Comment;
use CannyDain\Shorty\Comments\Models\CommentsSettingsEntry;
use CannyDain\Shorty\Comments\Views\AddCommentForm;
use CannyDain\Shorty\Comments\Views\CommentsView;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Consumers\ViewFactoryConsumer;

class ShortyCommentsManager implements CommentsManager, DependencyConsumer, RouterConsumer, ViewFactoryConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var ViewFactory
     */
    protected $_viewFactory;

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @param $guid
     * @param $returnURI
     * @return ViewInterface
     */
    public function getAdministrateCommentsView($guid, $returnURI)
    {
        $settings = $this->datasource()->getSettingsForObject($guid);

        $view = new CommentsView();
        $view->setComments($this->datasource()->getCommentsForObject($guid));
        $view->setDeleteCommentReturnToURI($returnURI);
        $view->setDeleteCommentURITemplate($this->_router->getURI(new Route(ShortyCommentsController::COMMENTS_CONTROLLER_ID, 'DeleteComment', array('#id#'))));
        $view->setCanAddComments(isset($settings[CommentsSettingsEntry::SETTING_CAN_POST_COMMENTS]) ? $settings[CommentsSettingsEntry::SETTING_CAN_POST_COMMENTS] : false);
        $view->setShowComments(isset($settings[CommentsSettingsEntry::SETTING_DISPLAY_COMMENTS]) ? $settings[CommentsSettingsEntry::SETTING_DISPLAY_COMMENTS] : false);
        $view->setGuid($guid);
        $view->setSaveSettingsURI($this->_router->getURI(new Route(ShortyCommentsController::COMMENTS_CONTROLLER_ID, 'SaveSettings')));

        $this->_dependencies->applyDependencies($view);

        return $view;
    }

    /**
     * @param $guid
     * @param $objectURI
     * @return ViewInterface
     */
    public function getCommentsViewForObject($guid, $objectURI)
    {
        $settings = $this->datasource()->getSettingsForObject($guid);
        if (!isset($settings[CommentsSettingsEntry::SETTING_DISPLAY_COMMENTS]) || $settings[CommentsSettingsEntry::SETTING_DISPLAY_COMMENTS] == 0)
            return new NullHTMLView();

        $view = new CommentsView();
        $view->setComments($this->datasource()->getCommentsForObject($guid));
        $view->setAddCommentView($this->getAddCommentForm($guid, $objectURI));

        $this->_dependencies->applyDependencies($view);

        return $view;
    }

    protected function getAddCommentForm($object, $returnURI)
    {
        $settings = $this->datasource()->getSettingsForObject($object);
        if (!isset($settings[CommentsSettingsEntry::SETTING_CAN_POST_COMMENTS]) || $settings[CommentsSettingsEntry::SETTING_CAN_POST_COMMENTS] == 0)
            return new NullHTMLView();

        $view = new AddCommentForm();
        $view->setComment(new Comment());
        $view->setReturnURI($returnURI);
        $view->setPostURI($this->_router->getURI(new Route(ShortyCommentsController::COMMENTS_CONTROLLER_ID, 'AddComment')));

        $view->getComment()->setObjectGUID($object);
        $view->getComment()->setPostedDateTime(time());

        $this->_dependencies->applyDependencies($view);

        return $view;
    }

    /**
     * @param $guid
     * @return int
     */
    public function getCommentCountForObject($guid)
    {
        return count($this->datasource()->getCommentsForObject($guid));
    }

    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new ShortyCommentsDatasource();
            $this->_dependencies->applyDependencies($datasource);
            $datasource->registerObjects();
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

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }

    public function consumeViewFactory(ViewFactory $dependency)
    {
        $this->_viewFactory = $dependency;
    }
}