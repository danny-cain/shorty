<?php

namespace CannyDain\ShortyCoreModules\AdminModule\DataAccess;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Shorty\Consumers\DataMapperConsumer;

class AdminModuleDataAccess implements DataMapperConsumer
{
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

    public function dependenciesConsumed()
    {

    }

    public function consumeDataMapper(DataMapper $dependency)
    {
        $this->_datamapper = $dependency;
    }
}