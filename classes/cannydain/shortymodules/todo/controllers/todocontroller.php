<?php

namespace CannyDain\ShortyModules\Todo\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\Controllers\ShortyModuleController;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\ShortyModules\Todo\Models\TodoEntry;
use CannyDain\ShortyModules\Todo\TodoModule;
use CannyDain\ShortyModules\Todo\Views\TodoEditView;
use CannyDain\ShortyModules\Todo\Views\TodoListView;

class TodoController extends ShortyModuleController implements SessionConsumer
{
    /**
     * @var SessionHelper
     */
    protected $_session;

    protected function _getUserID()
    {
        if ($this->_session == null)
            return 0;

        return $this->_session->getUserID();
    }

    public function Index()
    {
        if ($this->_getUserID() == 0)
            return new RedirectView("/");

        $view = new TodoListView();
        $this->_dependencies->applyDependencies($view);

        $view->setCompleteRoute(new Route(__CLASS__, 'Complete', array('#id#')));
        $view->setEditRoute(new Route(__CLASS__, 'Edit', array('#id#')));
        $view->setDeleteRoute(new Route(__CLASS__, 'Delete', array('#id#')));
        $view->setEntries($this->_getModule()->getDatasource()->getAllEntriesForUser($this->_getUserID()));
        $view->setCreateRoute(new Route(__CLASS__, 'Create'));

        return $view;
    }

    public function Complete($id)
    {
        if ($this->_getUserID() == 0)
            return new RedirectView("/");

        $uri = $this->_router->getURI(new Route(__CLASS__));
        $view = new RedirectView($uri);

        $entry = $this->_getModule()->getDatasource()->loadEntry($id);
        if ($entry->getOwner() != $this->_getUserID())
            return $view;

        if (!$this->_request->isPost())
            return $view;

        if ($entry->getCompleted() == 1)
            return $view;

        $entry->setCompleted(time());
        $entry->save();

        return $view;
    }

    public function Delete($id)
    {
        if ($this->_getUserID() == 0)
            return new RedirectView("/");

        $uri = $this->_router->getURI(new Route(__CLASS__));
        $view = new RedirectView($uri);

        $entry = $this->_getModule()->getDatasource()->loadEntry($id);
        if ($entry->getOwner() != $this->_getUserID())
            return $view;

        if (!$this->_request->isPost())
            return $view;

        $this->_getModule()->getDatasource()->deleteEntry($id);

        return $view;
    }

    public function Edit($id)
    {
        if ($this->_getUserID() == 0)
            return new RedirectView("/");

        $model = $this->_getModule()->getDatasource()->loadEntry($id);
        if ($model->getOwner() != $this->_getUserID())
            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));

        $view = new TodoEditView();
        $this->_dependencies->applyDependencies($view);

        $view->setEntry($model);
        $view->setSaveRoute(new Route(__CLASS__, 'Edit', array($id)));
        $view->setViewRoute(new Route(__CLASS__, 'Edit', array($id)));

        if ($view->updateFromPostAndReturnTrueIfPostedAndValid())
        {
            $view->getEntry()->save();
            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
        }

        return $view;
    }

    public function Create()
    {
        if ($this->_getUserID() == 0)
            return new RedirectView("/");

        $model = new TodoEntry();
        $this->_dependencies->applyDependencies($model);
        $model->setOwner($this->_getUserID());

        $view = new TodoEditView();
        $this->_dependencies->applyDependencies($view);

        $view->setEntry($model);
        $view->setSaveRoute(new Route(__CLASS__, 'Create'));
        $view->setViewRoute(null);

        if ($view->updateFromPostAndReturnTrueIfPostedAndValid())
        {
            $view->getEntry()->save();
            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
        }

        return $view;
    }

    protected function _getModuleClassname()
    {
        return TodoModule::TODO_MODULE_CLASS;
    }

    /**
     * @return TodoModule
     */
    protected function _getModule()
    {
        return parent::_getModule();
    }


    public function consumeSession(SessionHelper $session)
    {
        $this->_session = $session;
    }
}