<?php

namespace CannyDain\Lib\Utils\Date;

class DateFormatManager
{
    protected $_dateFormat = 'yy-mm-dd';
    protected $_timeFormat = 'H:i';
    protected $_dateTimeFormat = 'yy-mm-dd H:i';

    public function __construct($dateFormat = 'yy-mm-dd', $timeFormat = 'H:i', $dateTimeFormat = null)
    {
        if ($dateTimeFormat == null)
            $dateTimeFormat = $dateFormat.' '.$timeFormat;

        $this->_dateFormat = $dateFormat;
        $this->_timeFormat = $timeFormat;
        $this->_dateTimeFormat = $dateTimeFormat;
    }

    public function getFormattedDate($timestamp = null)
    {
        return date($this->_dateFormat, $timestamp);
    }

    public function getFormattedTime($timestamp = null)
    {
        return date($this->_timeFormat, $timestamp);
    }

    public function getFormattedDateTime($timestamp = null)
    {
        return date($this->_dateTimeFormat, $timestamp);
    }
}