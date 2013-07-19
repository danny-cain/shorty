<?php

namespace CannyDain\Shorty\TimeTracking\Controllers;

use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Consumers\TimeEntryConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Shorty\TimeTracking\TimeTracker;

class TimeTrackingController extends ShortyController implements TimeEntryConsumer, RouterConsumer, RequestConsumer
{
    const CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var RouterInterface
     */
    protected $_router;

    protected $_dependencies;

    /**
     * @var TimeTracker
     */
    protected $_timeTracker;

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return false;
    }

    public function TrackTime()
    {
        $view = $this->_timeTracker->getAddTimeViewForObject(null, $this->_router->getURI(new Route(__CLASS__, 'TrackTime')));
        if ($this->_request->isPost())
        {
            $view->updateModelFromRequest($this->_request);
            $this->_timeTracker->addTimeToObject($view->getObjectGUID(), $view->getEntry()->getStart(), $view->getEntry()->getEnd(), $view->getEntry()->getComment());

            return new RedirectView($view->getReturnURI());
        }

        return $view;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeTimeTracker(TimeTracker $dependency)
    {
        $this->_timeTracker = $dependency;
    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }
}