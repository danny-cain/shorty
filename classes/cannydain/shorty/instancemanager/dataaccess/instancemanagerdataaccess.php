<?php

namespace CannyDain\Shorty\InstanceManager\DataAccess;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\Shorty\InstanceManager\Models\BaseTypeDefinition;
use CannyDain\Shorty\InstanceManager\Models\InstanceDefinition;

class InstanceManagerDataAccess implements DataMapperConsumer
{
    /**
     * @var DataMapper
     */
    protected $_datamapper;

    public function registerObjects()
    {
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile(dirname(dirname(__FILE__)).'/datadictionary/objectmapping.json', $this->_datamapper);
    }

    /**
     * @param $interfaceOfBaseClass
     * @return BaseTypeDefinition
     */
    public function getBaseTypeByInterfaceOrBaseClass($interfaceOfBaseClass)
    {
        $results = $this->_datamapper->getAllObjectsViaEqualityFilter('\\CannyDain\\Shorty\\InstanceManager\\Models\\BaseTypeDefinition', array
        (
            'type' => $interfaceOfBaseClass
        ));

        if (count($results) > 0)
            return array_shift($results);

        return null;
    }

    /**
     * @param $baseTypeID
     * @param $instanceClassName
     * @return InstanceDefinition
     */
    public function getInstanceByBaseTypeAndClassName($baseTypeID, $instanceClassName)
    {
        $results = $this->_datamapper->getAllObjectsViaEqualityFilter('\\CannyDain\\Shorty\\InstanceManager\\Models\\InstanceDefinition', array
        (
            'baseType' => $baseTypeID,
            'class' => $instanceClassName
        ));

        if (count($results) > 0)
            return array_shift($results);

        return null;
    }

    public function saveBaseType(BaseTypeDefinition $type)
    {
        $this->_datamapper->saveObject($type);
    }

    /**
     * @return BaseTypeDefinition[]
     */
    public function getAllBaseTypes()
    {
        return $this->_datamapper->getAllObjects('\\CannyDain\\Shorty\\InstanceManager\\Models\\BaseTypeDefinition');
    }

    public function saveInstance(InstanceDefinition $def)
    {
        $this->_datamapper->saveObject($def);
    }

    /**
     * @param $baseTypeID
     * @return InstanceDefinition[]
     */
    public function getInstancesByBaseType($baseTypeID)
    {
        return $this->_datamapper->getAllObjectsViaEqualityFilter('\\CannyDain\\Shorty\\InstanceManager\\Models\\InstanceDefinition', array
        (
            'baseType' => $baseTypeID
        ));
    }

    /**
     * @param $instanceID
     * @return InstanceDefinition
     */
    public function getInstanceByID($instanceID)
    {
        return $this->_datamapper->loadObject('\\CannyDain\\Shorty\\InstanceManager\\Models\\InstanceDefinition', array('id' => $instanceID));
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