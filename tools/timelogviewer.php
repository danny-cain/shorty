<?php

use CannyDain\Shorty\TimeTracking\TimeTracker;

require dirname(__FILE__).'/initialise.php';

class TimeLogViewerMain implements \CannyDain\Shorty\Execution\AppMain, \CannyDain\Shorty\Consumers\TimeEntryConsumer
{
    /**
     * @var \CannyDain\Shorty\TimeTracking\TimeTracker
     */
    protected $_timeTracker;

    public function main()
    {
        $options = getopt('u:s:e:');
        $user = null;
        $start = null;
        $end = null;

        if (isset($options['u']))
            $user = $options['u'];

        if (isset($options['s']))
            $start = $options['s'];

        if (isset($options['e']))
            $end = $options['e'];

        if ($user == null || $start == null || $end == null)
            $this->_displayHelp();
        else
            $this->_displayTimeLog($user, $start, $end);
    }

    protected function _displayTimeLog($user, $start, $end)
    {
        $log = $this->_timeTracker->getLoggedTimeForUserOverPeriod($user, strtotime($start), strtotime($end));
        $parts = array();

        if ($log->getHours() > 0)
            $parts[] = $log->getHours().' hours';

        if ($log->getHours() > 0 || $log->getMinutes() > 0)
            $parts[] = $log->getMinutes().' minutes';

        $parts[] = $log->getSeconds().' seconds';

        echo 'Time Logged: '.implode(' ', $parts)."\r\n";
    }

    protected function _displayHelp()
    {
        echo "**********\r\n";
        echo " Time Log Viewer\r\n";
        echo " This utility will display the amount of time logged by the specified user over the specified period\r\n";
        echo " Usage: php timelogviewer.php -u<userID> -s<yyyy-mm-dd> -e<yyyy-mm-dd>\r\n";
        echo "**********\r\n";
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeTimeTracker(TimeTracker $dependency)
    {
        $this->_timeTracker = $dependency;
    }
}

ShortyInit::main(new TimeLogViewerMain());