<?php

namespace CannyDain\Lib\DataMapping;

use CannyDain\Lib\DataMapping\Interfaces\ModelFactoryInterface;
use CannyDain\Lib\DataMapping\Models\LinkDefinition;
use CannyDain\Lib\DataMapping\Models\ObjectDefinition;

interface DataMapperInterface
{
    public function dataStructureCheckForObject($type);

    public function dataStructureCheck();

    public function createObjectFromData($class, $data);

    public function registerModelFactory($objectName, ModelFactoryInterface $factory);

    public function addObjectDefinition(ObjectDefinition $def);

    public function addLinkDefinition(LinkDefinition $def);

    public function countObjects($className);

    public function getLinkTableName($object1, $object2);

    public function getObjectsViaLink($selectObject, $linkObject, $clauses = array(), $parameters = array(), $orderBy = '', $startAt = 0, $maxRecords = null, $extraSelects = array());

    public function getObjectsWithCustomClauses($className, $clauses = array(), $parameters = array(), $orderBy = '', $startAt = 0, $maxRecords = null, $extraSelects = array());

    public function getAllObjectsViaEqualityFilter($className, $filters = array(), $orderBy = '');

    public function getAllObjects($className);

    public function deleteObject($className, $ids);

    /**
     * @param $className
     * @param array $id associative array of column => value
     * @throws Exceptions\ObjectDefinitionNotFoundException
     */
    public function loadObject($className, $id);

    public function saveObject($object);

    public function getTableNameForObject($class);
}