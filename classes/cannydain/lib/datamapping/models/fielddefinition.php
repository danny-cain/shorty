<?php

namespace CannyDain\Lib\DataMapping\Models;

class FieldDefinition
{
    const DATA_TYPE_STRING = 'varchar';
    const DATA_TYPE_TEXT = 'text';
    const DATA_TYPE_INTEGER = 'int';
    const DATA_TYPE_DATETIME = 'datetime';
    const DATA_TYPE_DATE = 'date';
    const DATA_TYPE_TIME = 'time';
    const DATA_TYPE_ARRAY = 'array';

    protected $_columnName = '';
    protected $_propertyName = '';
    protected $_dataType = '';
    protected $_size = 0;

    public function setColumnName($columnName)
    {
        $this->_columnName = $columnName;
    }

    public function getColumnName()
    {
        return $this->_columnName;
    }

    public function setDataType($dataType)
    {
        $this->_dataType = $dataType;
    }

    public function getDataType()
    {
        return $this->_dataType;
    }

    public function getSQLDataType()
    {
        $type = $this->getDataType();
        switch($type)
        {
            case self::DATA_TYPE_ARRAY:
                $type = 'text';
                break;
        }

        return $type;
    }

    public function setPropertyName($propertyName)
    {
        $this->_propertyName = $propertyName;
    }

    public function getPropertyName()
    {
        return $this->_propertyName;
    }

    public function setSize($size)
    {
        $this->_size = $size;
    }

    public function getSize()
    {
        return $this->_size;
    }
}