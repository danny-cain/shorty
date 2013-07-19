<?php

namespace CannyDain\Shorty\TimeTracking\Models;

class LoggedTime
{
    protected $_hours = 0;
    protected $_minutes = 0;
    protected $_seconds = 0;

    public function __toString()
    {
        $parts = array();

        if ($this->_hours == 1)
            $parts[] = '1 hour';
        elseif ($this->_hours > 0)
            $parts[] = $this->_hours.' hours';

        if ($this->_minutes == 1)
            $parts[] = '1 minute';
        elseif ($this->_minutes > 0 || $this->_hours > 0)
            $parts[] = $this->_minutes.' minutes';

        if ($this->_seconds == 1)
            $parts[] = '1 second';
        else
            $parts[] = $this->_seconds.' seconds';

        return implode(' ', $parts);
    }

    public function addTime($seconds)
    {
        $this->_seconds += $seconds;
        if ($this->_seconds < 60)
            return;

        $temp = $this->_seconds % 60;
        $this->_minutes += ($this->_seconds - $temp) / 60;
        $this->_seconds = $temp;

        if ($this->_minutes < 60)
            return;

        $temp = $this->_minutes % 60;
        $this->_hours += ($this->_minutes - $temp) / 60;
        $this->_minutes = $temp;
    }

    public function getHours()
    {
        return $this->_hours;
    }

    public function getMinutes()
    {
        return $this->_minutes;
    }

    public function getSeconds()
    {
        return $this->_seconds;
    }
}