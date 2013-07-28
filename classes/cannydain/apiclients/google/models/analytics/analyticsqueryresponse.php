<?php

namespace CannyDain\APIClients\Google\Models\Analytics;

use CannyDain\APIClients\Google\Models\Analytics\AnalyticsColumnDefinition;
use CannyDain\APIClients\Google\Models\Analytics\AnalyticsResponseRow;

class AnalyticsQueryResponse
{
    protected $_profileID = '';
    protected $_profileName = '';
    /**
     * @var AnalyticsColumnDefinition[]
     */
    protected $_headers = array();

    /**
     * @var AnalyticsResponseRow[]
     */
    protected $_rows = array();

    public static function ParseFromGAResponse(\Google_GaData $data)
    {
        $ret = new AnalyticsQueryResponse();

        /**
         * @var \Google_GaDataColumnHeaders[] $headers
         */
        $headers = $data->getColumnHeaders();

        /**
         * @var \Google_GaDataProfileInfo $profile
         */
        $profile = $data->getProfileInfo();

        $ret->_profileID = $profile->getProfileId();
        $ret->_profileName = $profile->getProfileName();
        foreach ($headers as $header)
            $ret->_headers[] = new AnalyticsColumnDefinition($header->getName(), $header->getColumnType(), $header->getDataType());

        foreach ($data->getRows() as $row)
            $ret->_rows[] = new AnalyticsResponseRow($row, $ret->getHeaders());

        return $ret;
    }

    public function addHeader(AnalyticsColumnDefinition $header) { $this->_headers[] = $header; }
    public function addRow(AnalyticsResponseRow $row) { $this->_rows[] = $row; }

    public function setHeaders($headers)
    {
        $this->_headers = $headers;
    }

    public function getHeaders()
    {
        return $this->_headers;
    }

    public function setProfileID($profileID)
    {
        $this->_profileID = $profileID;
    }

    public function getProfileID()
    {
        return $this->_profileID;
    }

    public function setProfileName($profileName)
    {
        $this->_profileName = $profileName;
    }

    public function getProfileName()
    {
        return $this->_profileName;
    }

    public function setRows($rows)
    {
        $this->_rows = $rows;
    }

    public function getRows()
    {
        return $this->_rows;
    }
}