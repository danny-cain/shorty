<?php

namespace CannyDain\Lib\DataMapping\Config;

use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Lib\DataMapping\DataMapperInterface;
use CannyDain\Lib\DataMapping\Models\FieldDefinition;
use CannyDain\Lib\DataMapping\Models\LinkDefinition;
use CannyDain\Lib\DataMapping\Models\LinkFieldDefinition;
use CannyDain\Lib\DataMapping\Models\ObjectDefinition;

class JSONFileDefinitionBuilder
{
    public function readFile($filename, DataMapperInterface $datamapper)
    {
        $this->processJSON(file_get_contents($filename), $datamapper);
    }

    public function processJSON($json, DataMapperInterface $datamapper)
    {
        $data = json_decode($json, true);

        if (!is_array($data))
            return;

        foreach ($data as $objectDef)
        {
            if (isset($objectDef['type']))
                $type = $objectDef['type'];
            else
                $type = 'object';

            switch($type)
            {
                case 'object':
                    $this->_processObjectDef($objectDef, $datamapper);
                    break;
                case 'link':
                    $this->_processLinkDefinition($objectDef, $datamapper);
                    break;
            }

        }
    }

    protected function _processLinkDefinition($definition, DataMapperInterface $datamapper)
    {
        $def = new LinkDefinition($definition['object1'], $definition['object2'], $definition['table']);

        foreach ($definition['fields'] as $field)
        {
            $def->addLinkField(new LinkFieldDefinition($field['object'], $field['objectField'], $field['linkName']));
        }

        $datamapper->addLinkDefinition($def);
    }

    protected function _processObjectDef($def, DataMapperInterface $datamapper)
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