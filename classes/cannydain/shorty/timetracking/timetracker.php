<?php

namespace CannyDain\Shorty\TimeTracking;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Consumers\UserControlConsumer;
use CannyDain\Shorty\TimeTracking\Controllers\TimeTrackingController;
use CannyDain\Shorty\TimeTracking\DataAccess\TimeEntryDataAccess;
use CannyDain\Shorty\TimeTracking\Models\TimeEntry;
use CannyDain\Shorty\TimeTracking\Views\AddTimeView;
use CannyDain\Shorty\TimeTracking\Views\TimeLogView;
use CannyDain\Shorty\UserControl\UserControl;

class TimeTracker implements DependencyConsumer, UserControlConsumer, RouterConsumer
{
    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var UserControl
     */
    protected $_session;

    public function getLoggedTimeForUserOverPeriod($userID, $periodStart, $periodEnd)
    {
        return $this->datasource()->getTimeLoggedForUserOverTimePeriod($userID, $periodStart, $periodEnd);
    }

    public function getLatestEntryTimeForUser($user)
    {
        $entry = $this->datasource()->getMostRecentTimeEntryByUser($user);

        if ($entry == null)
            return 0;

        return $entry->getEnd();
    }

    public function addTimeToObject($objectGUID, $start, $finish, $comment)
    {
        $model = new TimeEntry();
        $model->setGuid($objectGUID);
        $model->setStart($start);
        $model->setEnd($finish);
        $model->setComment($comment);
        $model->setUser($this->_session->getCurrentUserID());

        $this->datasource()->saveTimeEntry($model);
    }

    public function getTimeLogViewForObject($objectGUID, $objectURI)
    {
        $view =new TimeLogView();
        $this->_dependencies->applyDependencies($view);

        $view->setEntries($this->datasource()->loadTimeEntryByGUID($objectGUID));
        $view->setAddTimeView($this->getAddTimeViewForObject($objectGUID, $objectURI));

        return $view;
    }

    public function getAddTimeViewForObject($objectGUID, $returnURI)
    {
        $addTimeView = new AddTimeView();
        $this->_dependencies->applyDependencies($addTimeView);
        $addTimeView->setEntry(new TimeEntry());
        $addTimeView->setPostURI($this->_router->getURI(new Route(TimeTrackingController::CONTROLLER_CLASS_NAME, 'TrackTime')));
        $addTimeView->setReturnURI($returnURI);
        $addTimeView->setObjectGUID($objectGUID);

        return $addTimeView;
    }

    protected function datasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new TimeEntryDataAccess();
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

    public function consumeUserController(UserControl $dependency)
    {
        $this->_session = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }
}