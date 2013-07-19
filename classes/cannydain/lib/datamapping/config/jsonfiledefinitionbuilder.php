<?php

namespace CannyDain\Lib\DataMapping\Config;

use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Lib\DataMapping\Models\FieldDefinition;
use CannyDain\Lib\DataMapping\Models\ObjectDefinition;

class JSONFileDefinitionBuilder
{
    public function readFile($filename, DataMapper $datamapper)
    {
        $this->processJSON(file_get_contents($filename), $datamapper);
    }

    public function processJSON($json, DataMapper $datamapper)
    {
        $data = json_decode($json, true);

        if (!is_array($data))
            return;

        foreach ($data as $objectDef)
            $this->_processObjectDef($objectDef, $datamapper);
    }

    protected function _processObjectDef($def, DataMapper $datamapper)
    {
        $fields = array();

        foreach ($def['fields'] as $fieldDef)
        {
            $field = new FieldDefinition();
            $field->setColumnName($fieldDef['column']);
            $field->setDataType($fieldDef['type']);
            $field->setPropertyName($fieldDef['property']);
            $field->setSize($fieldDef['size']);

            $fields[] = $field;
        }

        $definition = new ObjectDefinition();
        $definition->setClassName($def['class']);
        $definition->setIncrementingIDField($def['auto_id']);
        $definition->setIdFields($def['id']);
        $definition->setTableName($def['table']);
        $definition->setFields($fields);

        $datamapper->addObjectDefinition($definition);
    }
}