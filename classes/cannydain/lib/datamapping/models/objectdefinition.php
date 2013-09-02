<?php

namespace CannyDain\Lib\DataMapping\Models;

class ObjectDefinition
{
    protected $_tableName = '';
    protected $_className = '';
    protected $_idFields = array();
    protected $_incrementingIDField = '';

    /**
     * @var FieldDefinition[]
     */
    protected $_fields = array();

    public function setIncrementingIDField($incrementingIDField)
    {
        $this->_incrementingIDField = $incrementingIDField;
    }

    public function getIncrementingIDField()
    {
        return $this->_incrementingIDField;
    }

    public function getFieldDefByColumnName($columnName)
    {
        foreach ($this->_fields as $field)
            if ($field->getColumnName() == $columnName)
                return $field;

        return null;
    }

    public function setClassName($className)
    {
        $this->_className = $className;
    }

    public function getClassName()
    {
        return $this->_className;
    }

    /**
     * @param FieldDefinition[] $fields
     */
    public function setFields($fields)
    {
        $this->_fields = $fields;
    }

    /**
     * @return FieldDefinition[]
     */
    public function getFields()
    {
        return $this->_fields;
    }

    public function setIdFields($idFields)
    {
        $this->_idFields = $idFields;
    }

    public function getIdFields()
    {
        return $this->_idFields;
    }

    public function setTableName($tableName)
    {
        $this->_tableName = $tableName;
    }

    public function getTableName()
    {
        return $this->_tableName;
    }
}