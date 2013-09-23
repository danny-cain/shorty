<?php

namespace CannyDain\Lib\DataMapping\DataDiff;

use CannyDain\Lib\DataMapping\DataDiff\Models\DiffEntry;
use CannyDain\Lib\DataMapping\Models\FieldDefinition;
use CannyDain\Lib\DataMapping\Models\ObjectDefinition;
use CannyDain\Lib\Database\Interfaces\DatabaseConnection;

class DataDiff
{
    /**
     * @var DatabaseConnection
     */
    protected $_database;

    /**
     * @param \CannyDain\Lib\Database\Interfaces\DatabaseConnection $database
     */
    public function setDatabase($database)
    {
        $this->_database = $database;
    }

    /**
     * @return \CannyDain\Lib\Database\Interfaces\DatabaseConnection
     */
    public function getDatabase()
    {
        return $this->_database;
    }

    /**
     * @param ObjectDefinition $object
     * @return DiffEntry[]
     */
    public function generateDiff(ObjectDefinition $object)
    {
        /**
         * @var FieldDefinition[] $objectFields
         */
        $objectFields = array();
        $ret = array();

        foreach ($object->getFields() as $field)
            $objectFields[$field->getColumnName()] = $field;

        $sql = 'EXPLAIN `'.$object->getTableName().'`';
        $results = $this->_database->query($sql);
        while ($row = $results->nextRow_AssociativeArray())
        {
            $name = $row['Field'];
            $type = $row['Type'];

            $pos = strpos($type, '(');
            if ($pos !== false)
            {
                $size = substr($type, $pos + 1);
                $size = substr($size, 0, strlen($size) - 1);
                $type = substr($type, 0, $pos);
            }
            else
                $size = 0;

            if (!isset($objectFields[$name]))
            {
                $ret[] = new DiffEntry(DiffEntry::ACTION_DROP, $name);
                continue;
            }

            $field = $objectFields[$name];
            unset($objectFields[$name]);

            if ($field->getSQLDataType() != $type || $field->getSize() != $size)
            {
                $ret[] = new DiffEntry(DiffEntry::ACTION_CHANGE, $name, $name, $field->getSize(), $field->getSQLDataType());
                continue;
            }
        }

        foreach ($objectFields as $field)
            $ret[] = new DiffEntry(DiffEntry::ACTION_ADD, $field->getColumnName(), $field->getColumnName(), $field->getSize(), $field->getSQLDataType());

        return $ret;
    }

    /**
     * @param ObjectDefinition $object
     * @param DiffEntry[] $diff
     */
    public function applyDiff(ObjectDefinition $object, $diff)
    {
        foreach ($diff as $entry)
        {
            switch($entry->getAction())
            {
                case DiffEntry::ACTION_ADD:
                    $this->_applyAdd($object, $entry);
                    break;
                case DiffEntry::ACTION_CHANGE:
                    $this->_applyChange($object, $entry);
                    break;
                case DiffEntry::ACTION_DROP:
                    $this->_applyDrop($object, $entry);
                    break;
            }
        }
    }

    protected function _applyChange(ObjectDefinition $object, DiffEntry $diff)
    {
        $type = $diff->getNewType();
        if ($diff->getNewSize() > 0)
            $type .= '('.$diff->getNewSize().')';

        $sql = 'ALTER TABLE `'.$object->getTableName().'` CHANGE `'.$diff->getColumn().'` `'.$diff->getNewName().'` '.$type;

        $this->_database->statement($sql);
    }

    protected function _applyAdd(ObjectDefinition $object, DiffEntry $diff)
    {
        $type = $diff->getNewType();
        if ($diff->getNewSize() > 0)
            $type .= '('.$diff->getNewSize().')';

        $sql = 'ALTER TABLE `'.$object->getTableName().'` ADD `'.$diff->getNewName().'` '.$type;

        $this->_database->statement($sql);
    }

    protected function _applyDrop(ObjectDefinition $object, DiffEntry $diff)
    {
        $sql = 'ALTER TABLE `'.$object->getTableName().'` DROP `'.$diff->getColumn().'`';
        $this->_database->statement($sql);
    }
}