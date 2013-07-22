<?php

namespace CannyDain\ShortyCoreModules\AdminModule\DataAccess;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\ShortyCoreModules\AdminModule\Models\DashboardEntry;
use CannyDain\ShortyCoreModules\Diary\Models\DiaryEntry;

class AdminModuleDataAccess implements DataMapperConsumer
{
    const OBJECT_TYPE_DASHBOARD_ENTRY = '\\CannyDain\\ShortyCoreModules\\AdminModule\\Models\\DashboardEntry';

    /**
     * @var DataMapper
     */
    protected $_datamapper;

    public function registerObjects()
    {
        $file = dirname(dirname(__FILE__)).'/datadictionary/objects.json';
        if (!file_exists($file))
            return;

        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile($file, $this->_datamapper);
    }

    public function saveDashboardEntry(DashboardEntry $entry)
    {
        $this->_datamapper->saveObject($entry);
    }

    /**
     * @param $id
     * @return DashboardEntry
     */
    public function loadDashboardEntry($id)
    {
        return $this->_datamapper->loadObject(self::OBJECT_TYPE_DASHBOARD_ENTRY, $id);
    }

    /**
     * @return DashboardEntry[]
     */
    public function getAllDashboardEntries()
    {
        return $this->_datamapper->getAllObjects(self::OBJECT_TYPE_DASHBOARD_ENTRY);
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDataMapper(DataMapper $dependency)
    {
        $this->_datamapper = $dependency;
    }
}