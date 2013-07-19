<?php

namespace CannyDain\Lib\DataMapping;

use CannyDain\Lib\DataMapping\DataDiff\DataDiff;
use CannyDain\Lib\DataMapping\DataDiff\Models\DiffEntry;
use CannyDain\Lib\DataMapping\Exceptions\InvalidIDException;
use CannyDain\Lib\DataMapping\Exceptions\ObjectDefinitionNotFoundException;
use CannyDain\Lib\DataMapping\Models\FieldDefinition;
use CannyDain\Lib\DataMapping\Models\ObjectDefinition;
use CannyDain\Lib\Database\Interfaces\DatabaseConnection;

class DataMapper
{
    /**
     * @var ObjectDefinition[]
     */
    protected $_objects = array();

    /**
     * @var DatabaseConnection
     */
    protected $_database;

    public function __construct(DatabaseConnection $database)
    {
        $this->_database = $database;
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
        $sql = 'CREATE TABLE `'.$object->getTableName().'` ('.implode(', ', $fields).')';

        $this->_database->statement($sql);
    }

    public function addObjectDefinition(ObjectDefinition $def)
    {
        $this->_objects[$def->getClassName()] = $def;
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

    public function getObjectsWithCustomClauses($className, $clauses = array(), $parameters = array(), $orderBy = '', $startAt = 0, $maxRecords = null, $extraSelects = array())
    {
        $def = $this->_getDefinitionFromClassName($className);

        if ($def == null)
            throw new ObjectDefinitionNotFoundException;

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

    public function getAllObjectsViaEqualityFilter($className, $filters = array(), $orderBy = '')
    {
        $def = $this->_getDefinitionFromClassName($className);

        if ($def == null)
            throw new ObjectDefinitionNotFoundException;

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
            throw new ObjectDefinitionNotFoundException;

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
            throw new ObjectDefinitionNotFoundException;

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
     * @throws Exceptions\ObjectDefinitionNotFoundException
     */
    public function loadObject($className, $id)
    {
        $def = $this->_getDefinitionFromClassName($className);

        if ($def == null)
            throw new ObjectDefinitionNotFoundException();

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

    protected function _createObjectFromRow(ObjectDefinition $def, $row)
    {
        $className = $def->getClassName();
        $object = new $className();

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