<?php

namespace CannyDain\ShortyModules\Comments\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Shorty\Consumers\EventConsumer;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\Consumers\UserConsumer;
use CannyDain\Shorty\Controllers\ShortyModuleController;
use CannyDain\Shorty\Events\EventManager;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\Shorty\Helpers\UserHelper;
use CannyDain\ShortyModules\Comments\CommentsModule;
use CannyDain\ShortyModules\Comments\Views\AddCommentForm;
use CannyDain\ShortyModules\Todo\Models\TodoEntry;

class CommentsController extends ShortyModuleController implements SessionConsumer, EventConsumer, UserConsumer
{
    const COMMENTS_CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var EventManager
     */
    protected $_events;

    /**
     * @var SessionHelper
     */
    protected $_session;

    /**
     * @var UserHelper
     */
    protected $_users;

    protected function _getUserID()
    {
        return $this->_session->getUserID();
    }

    protected function _getName()
    {
        if ($this->_getUserID() == 0)
            return 'Guest';

        return $this->_users->getDisplayNameFromID($this->_getUserID());
    }

    public function PostComment()
    {
        $view = new AddCommentForm();
        $view->setComment($this->_getModule()->getDatasource()->createComment());
        $view->setPostRoute(new Route(__CLASS__, 'PostComment'));
        $view->getComment()->setAuthor($this->_getName());

        $this->_dependencies->applyDependencies($view);

        if ($view->updateFromPostAndReturnTrueIfPostedAndValid())
        {
            $view->getComment()->save();
            $this->_events->triggerEvent(CommentsModule::EVENT_COMMENT_POSTED, array($view->getComment()));
        }

        return new RedirectView($view->getReturnURL());
    }

    protected function _getModuleClassname()
    {
        return CommentsModule::COMMENTS_MODULE_CLASS;
    }

    /**
     * @return CommentsModule
     */
    protected function _getModule()
    {
        return parent::_getModule();
    }

    public function consumeSession(SessionHelper $session)
    {
        $this->_session = $session;
    }

    public function consumeEventManager(EventManager $eventManager)
    {
        $this->_events = $eventManager;
    }

    public function consumerUserHelper(UserHelper $helper)
    {
        $this->_users = $helper;
    }
}