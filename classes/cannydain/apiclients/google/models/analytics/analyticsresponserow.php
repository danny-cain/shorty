<?php

namespace CannyDain\APIClients\Google\Models\Analytics;

class AnalyticsResponseRow
{
    protected $_data = array();
    /**
     * @var AnalyticsColumnDefinition[]
     */
    protected $_columnHeaders = array();

    /**
     * @param array $_data
     * @param AnalyticsColumnDefinition[] $columnDef
     */
    function __construct($_data = array(), $columnDef = array())
    {
        $this->_data = $_data;
        $this->_columnHeaders = $columnDef;
    }

    public function getValueByHeader($header)
    {
        foreach ($this->_columnHeaders as $index => $hdr)
        {
            if ($hdr->getColName() == $header)
                return $this->_data[$index];
        }

        return null;
    }

    public function getDataByIndex($index)
    {
        if (!isset($this->_data[$index]))
            return null;

        return $this->_data[$index];
    }

    public function setData($data)
    {
        $this->_data = $data;
    }

    public function getData()
    {
        return $this->_data;
    }
}