<?php

namespace CannyDain\Shorty\TimeTracking\DataAccess;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\Shorty\TimeTracking\Models\LoggedTime;
use CannyDain\Shorty\TimeTracking\Models\TimeEntry;

class TimeEntryDataAccess implements DataMapperConsumer
{
    const OBJECT_TIME_ENTRY = '\\CannyDain\\Shorty\\TimeTracking\\Models\\TimeEntry';

    /**
     * @var DataMapper
     */
    protected $_datamapper;

    /**
     * @param $userID
     * @param $periodStart
     * @param $periodEnd
     * @return LoggedTime
     */
    public function getTimeLoggedForUserOverTimePeriod($userID, $periodStart, $periodEnd)
    {
        /**
         * @var TimeEntry[] $results
         */
        $results = $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_TIME_ENTRY, array
        (
            'user = :user',
            'end >= :start',
            'end <= :end',
        ), array
        (
            'user' => $userID,
            'start' => date('Y-m-d H:i:s', $periodStart),
            'end' => date('Y-m-d H:i:s', $periodEnd)
        ));

        $ret = new LoggedTime();
        foreach ($results as $result)
        {
            $ret->addTime($result->getTimeInSeconds());
        }

        return $ret;
    }

    /**
     * @param $user
     * @return TimeEntry
     */
    public function getMostRecentTimeEntryByUser($user)
    {
        $results = $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_TIME_ENTRY, array
        (
            'user = :user'
        ), array
        (
            'user' => $user
        ),'end DESC', 0, 1);

        return array_shift($results);
    }

    public function registerObjects()
    {
        $file = dirname(dirname(__FILE__)).'/datadictionary/timeentry.json';
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile($file, $this->_datamapper);

        $this->_datamapper->dataStructureCheckForObject(self::OBJECT_TIME_ENTRY);
    }

    public function saveTimeEntry(TimeEntry $entry)
    {
        $this->_datamapper->saveObject($entry);
    }

    /**
     * @param $guid
     * @return TimeEntry[]
     */
    public function loadTimeEntryByGUID($guid)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_TIME_ENTRY, array
        (
            'object = :guid'
        ), array
        (
            'guid' => $guid
        ),'`start` DESC');
    }

    /**
     * @param $id
     * @return TimeEntry[]
     */
    public function loadTimeEntryByID($id)
    {
        return $this->_datamapper->loadObject(self::OBJECT_TIME_ENTRY, array('id' => $id));
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDataMapper(DataMapper $dependency)
    {
        $this->_datamapper = $dependency;
    }
}