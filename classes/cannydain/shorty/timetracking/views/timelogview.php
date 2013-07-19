<?php

namespace CannyDain\Shorty\TimeTracking\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Shorty\Consumers\UserControlConsumer;
use CannyDain\Shorty\TimeTracking\Models\TimeEntry;
use CannyDain\Shorty\UserControl\UserControl;

class TimeLogView extends HTMLView implements UserControlConsumer
{
    /**
     * @var TimeEntry[]
     */
    protected $_entries = array();

    /**
     * @var UserControl
     */
    protected $_userControl;

    /**
     * @var AddTimeView
     */
    protected $_addTimeView;

    public function display()
    {
        foreach ($this->_entries as $entry)
            $this->_displayEntry($entry);

        if ($this->_addTimeView != null)
            $this->_addTimeView->display();
    }

    protected function _displayEntry(TimeEntry $entry)
    {
        echo '<div class="timeEntry">';
            echo '<div class="user">';
                echo $this->_userControl->getUsernameFromID($entry->getUser());
            echo '</div>';

            echo '<div class="time">';
                echo $this->_getTimeDifferenceForDisplay($entry->getStart(), $entry->getEnd());
            echo '</div>';

            echo '<div class="comment">';
                echo $entry->getComment();
            echo '</div>';
        echo '</div>';
    }

    protected function _getTimeDifferenceForDisplay($start, $end)
    {
        $negative = false;

        $diff = $end - $start;
        if ($diff < 0)
        {
            $negative = true;
            $diff = $diff * -1;
        }

        $seconds = $diff % 60;
        $diff = ($diff - $seconds) / 60;

        $minutes = $diff % 60;
        $diff = ($diff - $minutes) / 60;

        $hours = $diff;
        $segments = array();

        if ($negative)
            $segments[] = 'minus';

        if ($hours > 0)
            $segments[] = $hours.' hours';

        if ($minutes > 0 || $hours  > 0)
            $segments[] = $minutes.' minutes';

        $segments[] = $seconds.' seconds';

        return implode(' ', $segments);
    }

    /**
     * @param \CannyDain\Shorty\TimeTracking\Views\AddTimeView $addTimeView
     */
    public function setAddTimeView($addTimeView)
    {
        $this->_addTimeView = $addTimeView;
    }

    /**
     * @return \CannyDain\Shorty\TimeTracking\Views\AddTimeView
     */
    public function getAddTimeView()
    {
        return $this->_addTimeView;
    }

    public function setEntries($entries)
    {
        $this->_entries = $entries;
    }

    public function getEntries()
    {
        return $this->_entries;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeUserController(UserControl $dependency)
    {
        $this->_userControl = $dependency;
    }
}