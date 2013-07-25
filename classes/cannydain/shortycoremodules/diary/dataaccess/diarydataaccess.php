<?php

namespace CannyDain\ShortyCoreModules\Diary\DataAccess;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\ShortyCoreModules\Diary\Models\DiaryEntry;

class DiaryDataAccess implements DataMapperConsumer
{
    const OBJECT_TYPE_DIARY_ENTRY = '\\CannyDain\\ShortyCoreModules\\Diary\\Models\\DiaryEntry';

    /**
     * @var DataMapper
     */
    protected $_datamapper;

    public function registerObjects()
    {
        $file = dirname(dirname(__FILE__)).'/datadictionary/objects.json';
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile($file, $this->_datamapper);
    }

    /**
     * @param $userID
     * @param $periodStart
     * @param $periodEnd
     * @return DiaryEntry[]
     */
    public function getPublicEntriesForPeriod($userID, $periodStart, $periodEnd)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_TYPE_DIARY_ENTRY, array
        (
            '((`start` > :start AND `start` < :end) OR (`end` > :start AND `end` < :end))',
            'user = :user',
            'public = 1',
        ), array
        (
            'start' => date('Y-m-d H:i:s', $periodStart),
            'end' => date('Y-m-d H:i:s', $periodEnd),
            'user' => $userID
        ), '`start` ASC');
    }

    /**
     * @param $userID
     * @param $periodStart
     * @param $periodEnd
     * @return DiaryEntry[]
     */
    public function getEntriesForPeriod($userID, $periodStart, $periodEnd)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_TYPE_DIARY_ENTRY, array
        (
            '((`start` > :start AND `start` < :end) OR (`end` > :start AND `end` < :end))',
            'user = :user'
        ), array
        (
            'start' => date('Y-m-d H:i:s', $periodStart),
            'end' => date('Y-m-d H:i:s', $periodEnd),
            'user' => $userID
        ), '`start` ASC');
    }

    public function saveEntry(DiaryEntry $entry)
    {
        $this->_datamapper->saveObject($entry);
    }

    /**
     * @param $id
     * @return DiaryEntry
     */
    public function getEntry($id)
    {
        return $this->_datamapper->loadObject(self::OBJECT_TYPE_DIARY_ENTRY, $id);
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDataMapper(DataMapper $dependency)
    {
        $this->_datamapper = $dependency;
    }
}