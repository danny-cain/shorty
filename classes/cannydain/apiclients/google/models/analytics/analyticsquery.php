<?php

namespace CannyDain\APIClients\Google\Models\Analytics;

class AnalyticsQuery
{
    protected $_profileID = '';
    protected $_metrics = array();
    protected $_dimensions = array();
    protected $_startDate = 0;
    protected $_endDate = 0;

    function __construct($_profileID = '', $_startDate = null, $_endDate= null, $_metrics = array(), $_dimensions = array())
    {
        if ($_startDate == null)
            $_startDate = strtotime('2005-01-01');
        if ($_endDate == null)
            $_endDate = time();

        $this->_dimensions = $_dimensions;
        $this->_endDate = $_endDate;
        $this->_metrics = $_metrics;
        $this->_profileID = $_profileID;
        $this->_startDate = $_startDate;
    }

    public function setDimensions($dimensions)
    {
        $this->_dimensions = $dimensions;
    }

    public function getDimensions()
    {
        return $this->_dimensions;
    }

    public function setEndDate($endDate)
    {
        $this->_endDate = $endDate;
    }

    public function getEndDate()
    {
        return $this->_endDate;
    }

    public function setMetrics($metrics)
    {
        $this->_metrics = $metrics;
    }

    public function getMetrics()
    {
        return $this->_metrics;
    }

    public function setProfileID($profileID)
    {
        $this->_profileID = $profileID;
    }

    public function getProfileID()
    {
        return $this->_profileID;
    }

    public function setStartDate($startDate)
    {
        $this->_startDate = $startDate;
    }

    public function getStartDate()
    {
        return $this->_startDate;
    }
}