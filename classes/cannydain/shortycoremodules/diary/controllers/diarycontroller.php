<?php

namespace CannyDain\ShortyCoreModules\Diary\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Shorty\Consumers\UserControlConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Shorty\UserControl\UserControl;
use CannyDain\ShortyCoreModules\Diary\DataAccess\DiaryDataAccess;
use CannyDain\ShortyCoreModules\Diary\Models\DiaryEntry;
use CannyDain\ShortyCoreModules\Diary\Views\CalendarView;
use CannyDain\ShortyCoreModules\Diary\Views\EditDiaryEntryView;

class DiaryController extends ShortyController implements UserControlConsumer
{
    const CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var UserControl
     */
    protected $_session;

    public function Index()
    {
        $view = new CalendarView();

        $start = strtotime(date('Y-m-01'));
        $end = strtotime("+1 month", $start);

        $view->setCreateRoute(new Route(self::CONTROLLER_CLASS_NAME, 'CreateEntry'));
        $view->setMonth(date('m'));
        $view->setYear(date('Y'));
        $view->setEntries($this->datasource()->getEntriesForPeriod($this->_session->getCurrentUserID(), $start, $end));
        $this->_dependencies->applyDependencies($view);

        return $view;
    }

    public function DeleteEntry($id) {}
    public function UpcomingTasks($userID = null) {}

    public function EditEntry($id)
    {
        $view = $this->_factory_EditEntry($this->datasource()->getEntry($id));

        //todo validate user

        if ($this->_request->isPost())
        {
            $view->updateFromRequest($this->_request);
            $this->datasource()->saveEntry($view->getEntry());

            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
        }

        return $view;
    }

    public function CreateEntry()
    {
        $view = $this->_factory_EditEntry(new DiaryEntry());
        $view->getEntry()->setUser($this->_session->getCurrentUserID());

        if ($this->_request->isPost())
        {
            $view->updateFromRequest($this->_request);
            $this->datasource()->saveEntry($view->getEntry());

            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
        }

        return $view;
    }

    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new DiaryDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    protected function _factory_EditEntry(DiaryEntry $entry)
    {
        $view = new EditDiaryEntryView();
        $this->_dependencies->applyDependencies($view);

        $view->setEntry($entry);
        if ($entry->getId() > 0)
            $view->setSaveRoute(new Route(__CLASS__, 'EditEntry', array($entry->getId())));
        else
            $view->setSaveRoute(new Route(__CLASS__, 'CreateEntry'));

        return $view;
    }

    public function consumeUserController(UserControl $dependency)
    {
        $this->_session = $dependency;
    }
}