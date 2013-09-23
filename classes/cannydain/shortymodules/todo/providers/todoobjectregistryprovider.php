<?php

namespace CannyDain\ShortyModules\Todo\Providers;

use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Lib\GUIDS\Models\ObjectInfoModel;
use CannyDain\Lib\GUIDS\ObjectRegistryProvider;
use CannyDain\Shorty\Consumers\GUIDConsumer;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\ShortyModules\Tasks\Models\TaskModel;
use CannyDain\ShortyModules\Todo\Datasource\TodoDatasource;
use CannyDain\ShortyModules\Todo\Models\TodoEntry;
use CannyDain\ShortyModules\Todo\TodoModule;
use CannyDain\ShortyModules\Users\Models\User;

class TodoObjectRegistryProvider implements ObjectRegistryProvider, ModuleConsumer, GUIDConsumer
{
    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    /**
     * @var TodoDatasource
     */
    protected $_datasource;

    /**
     * @param string $searchTerm
     * @param string $typeLimit
     * @param int $limit
     * @return ObjectInfoModel[]
     */
    public function searchObjects($searchTerm, $typeLimit = null, $limit = 0)
    {
        if ($typeLimit != TodoEntry::TODO_OBJECT_NAME)
            return array();


        // todo search todo's
        return array();
    }

    /**
     * @param $guid
     * @return string
     */
    public function getNameOfObject($guid)
    {
        $type = $this->_guids->getType($guid);
        $id = $this->_guids->getID($guid);

        if ($type != TodoEntry::TODO_OBJECT_NAME)
            return '';

        return $this->_datasource->loadEntry($id)->getTitle();
    }

    /**
     * @return array
     */
    public function getKnownTypes()
    {
        return array
        (
            TodoEntry::TODO_OBJECT_NAME
        );
    }

    public function consumeGUIDManager(GUIDManagerInterface $guidManager)
    {
        $this->_guids = $guidManager;
    }

    public function consumeModuleManager(ModuleManager $manager)
    {
        /**
         * @var TodoModule $module
         */
        $module = $manager->getModuleByClassname(TodoModule::TODO_MODULE_CLASS);
        if ($module == null || !($module instanceof TodoModule))
            throw new \Exception("Unable to locate todo module");

        $this->_datasource = $module->getDatasource();
    }
}