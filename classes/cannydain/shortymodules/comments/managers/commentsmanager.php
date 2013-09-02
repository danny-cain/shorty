<?php

namespace CannyDain\ShortyModules\Comments\Managers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\Comments\CommentsModule;
use CannyDain\ShortyModules\Comments\Controllers\CommentsController;
use CannyDain\ShortyModules\Comments\Views\AddCommentForm;
use CannyDain\ShortyModules\Comments\Views\CommentsListView;

class CommentsManager implements \CannyDain\Lib\CommentsManager\CommentsManager, ModuleConsumer, DependencyConsumer
{
    /**
     * @var CommentsModule
     */
    protected $_commentsModule;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @param $guid
     * @param $objectURI
     * @return ViewInterface
     */
    public function getCommentsViewForObject($guid, $objectURI)
    {
        $view = new CommentsListView();
        $view->setComments($this->_datasource()->loadAllCommentsForObject($guid));


        $addCommentsForm = new AddCommentForm();
        $addCommentsForm->setComment($this->_datasource()->createComment());
        $addCommentsForm->setReturnURL($objectURI);
        $addCommentsForm->setPostRoute(new Route(CommentsController::COMMENTS_CONTROLLER_CLASS_NAME, 'PostComment'));
        $addCommentsForm->getComment()->setGuid($guid);

        $this->_dependencies->applyDependencies($addCommentsForm);

        $view->setAddCommentForm($addCommentsForm);

        return $view;
    }

    /**
     * @param $guid
     * @param $returnURI
     * @return ViewInterface
     */
    public function getAdministrateCommentsView($guid, $returnURI)
    {
        // TODO: Implement getAdministrateCommentsView() method.
    }

    /**
     * @param $guid
     * @return int
     */
    public function getCommentCountForObject($guid)
    {
        return $this->_datasource()->getCommentsCount($guid);
    }

    protected function _datasource()
    {
        return $this->_commentsModule->getDatasource();
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        $this->_commentsModule = $manager->getModuleByClassname(CommentsModule::COMMENTS_MODULE_CLASS);
    }

    public function consumeDependencies(DependencyInjector $dependencies)
    {
        $this->_dependencies = $dependencies;
    }
}
