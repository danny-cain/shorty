<?php

namespace CannyDain\Lib\DataMapping;

use CannyDain\Lib\DataMapping\DataDiff\DataDiff;
use CannyDain\Lib\DataMapping\DataDiff\Models\DiffEntry;
use CannyDain\Lib\DataMapping\Exceptions\InvalidIDException;
use CannyDain\Lib\DataMapping\Exceptions\ObjectDefinitionNotFoundException;
use CannyDain\Lib\DataMapping\Interfaces\ModelFactoryInterface;
use CannyDain\Lib\DataMapping\Models\FieldDefinition;
use CannyDain\Lib\DataMapping\Models\LinkDefinition;
use CannyDain\Lib\DataMapping\Models\ObjectDefinition;
use CannyDain\Lib\Database\Interfaces\DatabaseConnection;
use CannyDain\Lib\DependencyInjection\DependencyInjector;

class DataMapper implements DataMapperInterface
{
    /**
     * @var ObjectDefinition[]
     */
    protected $_objects = array();

    /**
     * @var LinkDefinition[]
     */
    protected $_linkDefinitions = array();

    /**
     * @var ModelFactoryInterface[]
     */
    protected $_modelFactories = array();

    /**
     * @var DatabaseConnection
     */
    protected $_database;

    /**
     * @var DependencyInjector
     */
    protected $_dependencyInjector;

    protected $_prefix = '';

    public function __construct(DatabaseConnection $database, DependencyInjector $dependencyInjector)
    {
        $this->_database = $database;
        $this->_dependencyInjector = $dependencyInjector;
    }

    public function setPrefix($prefix)
    {
        $this->_prefix = $prefix;
    }

    public function getPrefix()
    {
        return $this->_prefix;
    }

    public function dataStructureCheckForObject($type)
    {
        $object = $this->_getDefinitionFromClassName($type);

        if (!$this->_doesTableExist($object->getTableName()))
            $this->_installObject($object);

        $diff = $this->_extractDataStructureDiffForObject($object);
        if (count($diff) > 0)
            $this->_applyDiff($object, $diff);
    }

    public function dataStructureCheck()
    {
        foreach ($this->_objects as $object)
        {
            if (!$this->_doesTableExist($object->getTableName()))
                $this->_installObject($object);

            $diff = $this->_extractDataStructureDiffForObject($object);
            if (count($diff) > 0)
                $this->_applyDiff($object, $diff);
        }

        foreach ($this->_linkDefinitions as $link)
        {
            if (!$this->_doesTableExist($link->getLinkTableName()))
                $this->_installObject($this->_createObjectDefForLink($link));

            $diff = $this->_extractDataStructureDiffForLink($link);
            if (count($diff) > 0)
                $this->_applyLinkDiff($link, $diff);
        }
    }

    public function registerModelFactory($objectName, ModelFactoryInterface $factory)
    {
        $this->_modelFactories[$objectName] = $factory;
    }

    /**
     * @param LinkDefinition $link
     * @param DiffEntry[] $diff
     */
    protected function _applyLinkDiff(LinkDefinition $link, $diff)
    {
        $object = $this->_createObjectDefForLink($link);
        $diffBuilder = new DataDiff();
        $diffBuilder->setDatabase($this->_database);

        $diffBuilder->applyDiff($object, $diff);
    }

    /**
     * @param ObjectDefinition $object
     * @param DiffEntry[] $diff
     */
    protected function _applyDiff(ObjectDefinition $object, $diff)
    {
        $diffBuilder = new DataDiff();
        $diffBuilder->setDatabase($this->_database);

        $diffBuilder->applyDiff($object, $diff);
    }

    protected function _createObjectDefForLink(LinkDefinition $linkDefinition)
    {
        $object1 = $this->_getDefinitionFromClassName($linkDefinition->getObject1());
        $object2 = $this->_getDefinitionFromClassName($linkDefinition->getObject2());

        $objectDef = new ObjectDefinition();
        $objectDef->setTableName($linkDefinition->getLinkTableName());

        $idFields = array();
        $fieldDefinitions = array();
        foreach ($linkDefinition->getFields() as $field)
        {
            $newField = new FieldDefinition();
            switch($field->getObject())
            {
                case 1:
                    $fieldDef = $object1->getFieldDefByColumnName($field->getObjectField());
                    break;
                case 2:
                    $fieldDef = $object2->getFieldDefByColumnName($field->getObjectField());
                    break;
                default:
                    throw new \Exception("Unrecognised Object");
            }

            $newField->setDataType($fieldDef->getDataType());
            $newField->setSize($fieldDef->getSize());
            $newField->setColumnName($field->getLinkName());
            $idFields[] = $field->getLinkName();

            $fieldDefinitions[] = $newField;
        }
        $objectDef->setFields($fieldDefinitions);
        $objectDef->setIdFields($idFields);

        return $objectDef;
    }

    protected function _extractDataStructureDiffForLink(LinkDefinition $link)
    {
        $objectDef = $this->_createObjectDefForLink($link);
        $diffBuilder = new DataDiff();
        $diffBuilder->setDatabase($this->_database);

        return $diffBuilder->generateDiff($objectDef);
    }

    /**
     * @param ObjectDefinition $object
     * @return \CannyDain\Lib\DataMapping\DataDiff\Models\DiffEntry[]
     */
    protected function _extractDataStructureDiffForObject(ObjectDefinition $object)
    {
        $diffBuilder = new DataDiff();
        $diffBuilder->setDatabase($this->_database);

        return $diffBuilder->generateDiff($object);
    }

    protected function _doesTableExist($table)
    {
        $sql = 'SHOW TABLES LIKE \''.$table.'\'';
        try
        {
            $results = $this->_database->query($sql);

            return $results->getRowCount() > 0;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    protected function _installObject(ObjectDefinition $object)
    {
        $fields = array();
        $ids = array();

        foreach ($object->getFields() as $field)
        {
            $type = $field->getSQLDataType();
            if ($field->getSize() > 0)
                $type .= '('.$field->getSize().')';

            $fields[] = '`'.$field->getColumnName().'` '.$type;
        }

        foreach ($object->getIdFields() as $field)
        {
            $ids[] = '`'.$field.'`';
        }

        $fields[] = 'PRIMARY KEY('.implode(',', $ids).')';
        $sql = 'CREATE TABLE `'.$object->getTableName().'` ('.implode(', ', $fields).') DEFAULT CHARSET=utf8';

        $this->_database->statement($sql);
    }

    // todo - move these to database (getActualTableName method)
    public function addObjectDefinition(ObjectDefinition $def)
    {
        $def->setTableName($this->_prefix.$def->getTableName());
        $this->_objects[$def->getClassName()] = $def;
    }

    public function addLinkDefinition(LinkDefinition $def)
    {
        $def->setLinkTableName($this->_prefix.$def->getLinkTableName());
        $this->_linkDefinitions[] = $def;
    }

    public function getLinkTableName($object1, $object2)
    {
        $linkDef = $this->_getLinkDefinitionFromObjects($object1, $object2);

        return $linkDef->getLinkTableName();
    }

    public function countObjects($className)
    {
        $def = $this->_getDefinitionFromClassName($className);

        $sql = 'SELECT COUNT(*)
                FROM `'.$def->getTableName().'`';
        $result = $this->_database->query($sql,array());
        $row = $result->nextRow_IndexedArray();

        return $row[0];
    }

    public function getObjectsViaLink($selectObject, $linkObject, $clauses = array(), $parameters = array(), $orderBy = '', $startAt = 0, $maxRecords = null, $extraSelects = array())
    {
        $def = $this->_getLinkDefinitionFromObjects($selectObject, $linkObject);
        $o1Def = $this->_getDefinitionFromClassName($selectObject);
        $o2Def = $this->_getDefinitionFromClassName($linkObject);
        if ($def == null)
            throw new ObjectDefinitionNotFoundException($selectObject.'/'.$linkObject);

        if ($o1Def == null)
            throw new ObjectDefinitionNotFoundException($selectObject);

        if ($o2Def == null)
            throw new ObjectDefinitionNotFoundException($linkObject);

        $joinObjectClauses = array();
        $joinLinkClauses = array();

        $selectObjectIndex = $def->getObject1() == $selectObject ? 1 : 2;

        foreach ($def->getFields() as $field)
        {
            if ($field->getObject() == $selectObjectIndex)
            {
                $joinObjectClauses[] = 'object.'.$field->getObjectField().' = join.'.$field->getLinkName();
            }
            elseif ($field->getObject() != $selectObjectIndex)
            {
                $joinLinkClauses[] = 'link.'.$field->getObjectField().' = join.'.$field->getLinkName();
            }
        }

        $joins = array
        (
            'INNER JOIN `'.$def->getLinkTableName().'` `join` ON '.implode(' AND ', $joinObjectClauses),
            'INNER JOIN `'.$o2Def->getTableName().'` `link` ON '.implode(' AND ', $joinLinkClauses),
        );

        $where = '';
        if (count($clauses) > 0)
            $where = 'WHERE '.implode(' AND ', $clauses);

        $orderByClause = '';
        if ($orderBy != '')
            $orderByClause = 'ORDER BY '.$orderBy;

        $limitClause = '';
        if ($startAt > 0)
            $limitClause = 'LIMIT '.$startAt.', '.$maxRecords;
        elseif ($maxRecords != null)
            $limitClause = 'LIMIT '.$maxRecords;

        $extraSelects[] = '`object`.*';

        $sql = 'SELECT DISTINCT '.implode(', ', $extraSelects).'
                FROM `'.$o1Def->getTableName().'` `object`
                '.implode("\r\n", $joins).'
                '.$where.'
                '.$orderByClause.'
                '.$limitClause;

        $results = $this->_database->query($sql, $parameters);

        $ret = array();

        while ($row = $results->nextRow_AssociativeArray())
            $ret[] = $this->_createObjectFromRow($o1Def, $row);

        return $ret;
    }

    public function getObjectsWithCustomClauses($className, $clauses = array(), $parameters = array(), $orderBy = '', $startAt = 0, $maxRecords = null, $extraSelects = array())
    {
        $def = $this->_getDefinitionFromClassName($className);

        if ($def == null)
            throw new ObjectDefinitionNotFoundException($className);

        $where = '';
        if (count($clauses) > 0)
            $where = 'WHERE '.implode(' AND ', $clauses);

        $orderByClause = '';
        if ($orderBy != '')
            $orderByClause = 'ORDER BY '.$orderBy;

        $limitClause = '';
        if ($startAt > 0)
            $limitClause = 'LIMIT '.$startAt.', '.$maxRecords;
        elseif ($maxRecords != null)
            $limitClause = 'LIMIT '.$maxRecords;

        $extraSelects[] = '`table`.*';

        $sql = 'SELECT '.implode(', ', $extraSelects).'
                FROM `'.$def->getTableName().'` `table`
                '.$where.'
                '.$orderByClause.'
                '.$limitClause;

        $results = $this->_database->query($sql, $parameters);

        $ret = array();

        while ($row = $results->nextRow_AssociativeArray())
            $ret[] = $this->_createObjectFromRow($def, $row);

        return $ret;
    }

    public function createObjectFromData($class, $data)
    {
        $def = $this->_getDefinitionFromClassName($class);
        return $this->_createObjectFromRow($def, $data);
    }

    public function getAllObjectsViaEqualityFilter($className, $filters = array(), $orderBy = '')
    {
        $def = $this->_getDefinitionFromClassName($className);

        if ($def == null)
            throw new ObjectDefinitionNotFoundException($className);

        $whereClauses = array();
        $parameters = array();

        foreach ($filters as $column => $value)
        {
            $whereClauses[] = '`'.$column.'` = :'.$column;
            $parameters[$column] = $value;
        }

        $where = '';
        if (count($whereClauses) > 0)
            $where = 'WHERE '.implode(' AND ', $whereClauses);

        $sql = 'SELECT *
                FROM `'.$def->getTableName().'`
                '.$where;

        if ($orderBy != '')
            $sql .= ' ORDER BY '.$orderBy;

        $results = $this->_database->query($sql, $parameters);

        $ret = array();

        while ($row = $results->nextRow_AssociativeArray())
            $ret[] = $this->_createObjectFromRow($def, $row);

        return $ret;
    }

    public function getAllObjects($className)
    {
        $def = $this->_getDefinitionFromClassName($className);

        if ($def == null)
            throw new ObjectDefinitionNotFoundException($className);

        $sql = 'SELECT *
                FROM `'.$def->getTableName().'`';

        $results = $this->_database->query($sql);

        $ret = array();

        while ($row = $results->nextRow_AssociativeArray())
            $ret[] = $this->_createObjectFromRow($def, $row);

        return $ret;
    }

    public function deleteObject($className, $ids)
    {
        $def = $this->_getDefinitionFromClassName($className);

        if ($def == null)
            throw new ObjectDefinitionNotFoundException($className);

        $where = array();
        $params = array();

        foreach ($def->getIdFields() as $column)
        {
            if (!isset($ids[$column]))
                throw new InvalidIDException;

            $where[] = '`'.$column.'` = :'.$column;
            $params[$column] = $ids[$column];
        }

        $sql = 'DELETE FROM `'.$def->getTableName().'` WHERE '.implode(' AND ', $where);
        $this->_database->statement($sql, $params);
    }

    /**
     * @param $className
     * @param array $id associative array of column => value
     * @return null|object
     * @throws Exceptions\ObjectDefinitionNotFoundException
     */
    public function loadObject($className, $id)
    {
        $def = $this->_getDefinitionFromClassName($className);

        if ($def == null)
            throw new ObjectDefinitionNotFoundException($className);

        $where = array();
        $params = array();

        foreach ($id as $col => $val)
        {
            $field = $def->getFieldDefByColumnName($col);
            $where[] = $field->getColumnName().' = :'.$field->getColumnName();
            $params[$field->getColumnName()] = $val;
        }

        $sql = 'SELECT *
                FROM '.$def->getTableName().'
                WHERE '.implode(' AND ', $where);

        $results = $this->_database->query($sql, $params);
        return $this->_createObjectFromRow($def, $results->nextRow_AssociativeArray());
    }

    protected function _modelFactory($type, $row)
    {
        $model = null;
        if (isset($this->_modelFactories[$type]))
        {
            $model = $this->_modelFactories[$type]->createModel($type, $row);
        }

        if ($model == null)
            $model = new $type();

        $this->_dependencyInjector->applyDependencies($model);

        return $model;
    }

    protected function _createObjectFromRow(ObjectDefinition $def, $row)
    {
        $className = $def->getClassName();
        $object = $this->_modelFactory($className, $row);

        foreach ($def->getFields() as $field)
        {
            $val = $row[$field->getColumnName()];

            switch($field->getDataType())
            {
                case FieldDefinition::DATA_TYPE_DATE:
                case FieldDefinition::DATA_TYPE_DATETIME:
                case FieldDefinition::DATA_TYPE_TIME:
                    $val = strtotime($val);
                    break;
                case FieldDefinition::DATA_TYPE_ARRAY:
                    $val = json_decode($val, true);
                    if ($val == null)
                        $val = array();

                    break;
            }
            $this->_setPropertyOnObject($object, $field->getPropertyName(), $val);
        }

        return $object;
    }

    public function saveObject($object)
    {
        $def = $this->_getDefinitionFromObject($object);
        if ($def == null)
            throw new ObjectDefinitionNotFoundException(get_class($object));

        $idField = $def->getFieldDefByColumnName($def->getIncrementingIDField());
        if ($this->_getPropertyFromObject($object, $idField->getPropertyName()) < 1)
            $this->_generateNewID($def, $object);

        $exists = $this->_doesObjectExist($def, $object);
        if ($exists)
            $sql = 'UPDATE ';
        else
            $sql = 'INSERT INTO ';

        $params = array();
        $sql .= $def->getTableName().' SET ';
        $setters = array();

        foreach ($def->getFields() as $field)
        {
            $val = $this->_getPropertyFromObject($object, $field->getPropertyName());
            switch($field->getDataType())
            {
                case FieldDefinition::DATA_TYPE_DATE:
                    $val = date('Y-m-d', $val);
                    break;
                case FieldDefinition::DATA_TYPE_DATETIME:
                    $val = date('Y-m-d H:i:s', $val);
                    break;
                case FieldDefinition::DATA_TYPE_TIME:
                    $val = date('Y-m-d', $val);
                    break;
                case FieldDefinition::DATA_TYPE_ARRAY:
                    $val = json_encode($val);
                    break;
            }
            $params[$field->getColumnName()] = $val;
            if ($field->getColumnName() == $idField->getColumnName() && $exists)
                continue;

            $setters[] = '`'.$field->getColumnName().'` = :'.$field->getColumnName();
        }

        $sql .= implode(', ', $setters);

        if ($exists)
            $sql .= ' WHERE '.$idField->getColumnName().' = :'.$idField->getColumnName();

        $this->_database->statement($sql, $params);
    }

    protected function _generateNewID(ObjectDefinition $objectDef, $object)
    {
        $idField = $objectDef->getFieldDefByColumnName($objectDef->getIncrementingIDField());
        $select = $idField->getColumnName();
        $where = array();
        $params = array();

        foreach ($objectDef->getIdFields() as $colName)
        {
            if ($colName == $idField->getColumnName())
                continue;

            $field = $objectDef->getFieldDefByColumnName($colName);
            $where[] = $field->getColumnName().' = :'.$field->getColumnName();
            $params[$field->getColumnName()] = $this->_getPropertyFromObject($object, $field->getPropertyName());
        }

        $whereClause = '';
        if (count($where) > 0)
            $whereClause = 'WHERE '.implode(' AND ', $where);

        $sql = 'SELECT '.$select.'
                FROM '.$objectDef->getTableName().'
                '.$whereClause.'
                ORDER BY '.$idField->getColumnName().' DESC
                LIMIT 1';

        $results = $this->_database->query($sql, $params);
        $row = $results->nextRow_IndexedArray();
        $id = $row[0];

        if ($id == '')
            $id = 0;

        $id ++;
        $this->_setPropertyOnObject($object, $idField->getPropertyName(), $id);
    }

    protected function _doesObjectExist(ObjectDefinition $objectDef, $object)
    {
        $select = 'count(*)';
        $where = array();
        $params = array();

        foreach ($objectDef->getIdFields() as $columnName)
        {
            $field = $objectDef->getFieldDefByColumnName($columnName);
            $where[] = $field->getColumnName().' = '.':'.$field->getColumnName();
            $params[$field->getColumnName()] = $this->_getPropertyFromObject($object, $field->getPropertyName());
        }

        $sql = 'SELECT '.$select.'
                FROM '.$objectDef->getTableName().'
                WHERE '.implode(' AND ', $where);

        try
        {
            $result = $this->_database->query($sql, $params);
            $row = $result->nextRow_IndexedArray();

            return $row[0] > 0;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    protected function _setPropertyOnObject($object, $property, $value)
    {
        $reflectObject = new \ReflectionObject($object);
        $property = $reflectObject->getProperty($property);

        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    protected function _getPropertyFromObject($object, $property)
    {
        $reflectObject = new \ReflectionObject($object);
        $property = $reflectObject->getProperty($property);

        $property->setAccessible(true);
        return $property->getValue($object);
    }

    public function getTableNameForObject($class)
    {
        return $this->_getDefinitionFromClassName($class)->getTableName();
    }

    /**
     * @param $object
     * @return ObjectDefinition|null
     */
    protected function _getDefinitionFromClassName($object)
    {
        foreach ($this->_objects as $def)
        {
            if ($def->getClassName() == $object)
                return $def;
        }

        return null;
    }

    protected function _getLinkDefinitionFromObjects($object1, $object2)
    {
        foreach ($this->_linkDefinitions as $def)
        {
            if ($def->getObject1() == $object1 && $def->getObject2() == $object2)
                return $def;

            if ($def->getObject1() == $object2 && $def->getObject2() == $object1)
                return $def;
        }

        return null;
    }

    /**
     * @param $object
     * @return ObjectDefinition|null
     */
    protected function _getDefinitionFromObject($object)
    {
        foreach ($this->_objects as $objDef)
        {
            if (!is_a($object, $objDef->getClassName()))
                continue;

            return $objDef;
        }

        return null;
    }
}