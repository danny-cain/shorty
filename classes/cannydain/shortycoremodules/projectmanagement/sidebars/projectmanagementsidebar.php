<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\Sidebars;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Consumers\TimeEntryConsumer;
use CannyDain\Shorty\Consumers\UserControlConsumer;
use CannyDain\Shorty\Sidebars\Base\Sidebar;
use CannyDain\Shorty\Sidebars\Base\SidebarInterface;
use CannyDain\Shorty\TimeTracking\Models\TimeEntry;
use CannyDain\Shorty\TimeTracking\TimeTracker;
use CannyDain\Shorty\UserControl\UserControl;
use CannyDain\ShortyCoreModules\ProjectManagement\DataAccess\ProjectManagementDataAccess;

class ProjectManagementSidebar extends Sidebar implements UserControlConsumer, RouterConsumer, DependencyConsumer, TimeEntryConsumer
{
    /**
     * @var UserControl
     */
    protected $_session;

    /**
     * @var TimeTracker
     */
    protected $_timeManager;

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    protected function _getTitle()
    {
        return 'Project Management';
    }

    protected function _drawContent()
    {
        $monthStart = strtotime(date('Y').'-'.date('m').'-01');
        $user = $this->_session->getCurrentUserID();

        $timeEntered = $this->_timeManager->getLoggedTimeForUserOverPeriod($user, $monthStart, time());
        $mostRecent = $this->_timeManager->getLatestEntryTimeForUser($user);

        echo '<p>You have entered '.$timeEntered.' since '.date('Y-m-d', $monthStart).'</p>';

        if ($mostRecent > 0)
        echo '<p>You most recently entered time '.date('Y-m-d H:i:s', $mostRecent).'</p>';
    }

    protected function datasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new ProjectManagementDataAccess();
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

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }

    public function consumeUserController(UserControl $dependency)
    {
        $this->_session = $dependency;
    }

    public function consumeTimeTracker(TimeTracker $dependency)
    {
        $this->_timeManager = $dependency;
    }
}