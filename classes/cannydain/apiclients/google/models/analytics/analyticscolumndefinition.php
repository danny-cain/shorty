<?php

namespace CannyDain\APIClients\Google\Models\Analytics;

class AnalyticsColumnDefinition
{
    protected $_colName = '';
    protected $_colType = '';
    protected $_dataType = '';

    function __construct($_colName = '', $_colType = '', $_dataType = '')
    {
        $this->_colName = $_colName;
        $this->_colType = $_colType;
        $this->_dataType = $_dataType;
    }

    public function setColName($colName)
    {
        $this->_colName = $colName;
    }

    public function getColName()
    {
        return $this->_colName;
    }

    public function setColType($colType)
    {
        $this->_colType = $colType;
    }

    public function getColType()
    {
        return $this->_colType;
    }

    public function setDataType($dataType)
    {
        $this->_dataType = $dataType;
    }

    public function getDataType()
    {
        return $this->_dataType;
    }
}