<?php

namespace CannyDain\Shorty\Modules\DataAccess;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\Shorty\Modules\Models\ModuleStatus;

class ModulesDataLayer implements DataMapperConsumer
{
    /**
     * @var DataMapper
     */
    protected $_datamapper;

    public function registerObjects()
    {
        $file = dirname(dirname(__FILE__)).'/datadictionary/objectdefinitions.json';
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile($file, $this->_datamapper);
    }

    public function saveModuleInfo(ModuleStatus $module)
    {
        $this->_datamapper->saveObject($module);
    }

    /**
     * @return ModuleStatus[]
     */
    public function getAllKnownModules()
    {
        return $this->_datamapper->getAllObjects('\\CannyDain\\Shorty\\Modules\\Models\\ModuleStatus');
    }

    public function dependenciesConsumed()
    {
        // TODO: Implement dependenciesConsumed() method.
    }

    public function consumeDataMapper(DataMapper $dependency)
    {
        $this->_datamapper = $dependency;
    }
}