<?php

namespace CannyDain\ShortyModules\Todo\Datasource;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Shorty\DataAccess\ShortyDatasource;
use CannyDain\ShortyModules\Todo\Models\TodoEntry;

class TodoDatasource extends ShortyDatasource
{
    /**
     * @param $user
     * @return TodoEntry[]
     */
    public function getAllEntriesForUser($user)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(TodoEntry::TODO_OBJECT_NAME, array
        (
            'owner = :owner'
        ), array
        (
            'owner' => $user
        ), 'created ASC');
    }

    public function deleteEntry($id)
    {
        $this->_datamapper->deleteObject(TodoEntry::TODO_OBJECT_NAME, array('id' => $id));
    }

    /**
     * @param $id
     * @return TodoEntry
     */
    public function loadEntry($id)
    {
        return $this->_datamapper->loadObject(TodoEntry::TODO_OBJECT_NAME, array('id' => $id));
    }

    public function registerObjects()
    {
        $file = dirname(__FILE__).'/datadictionary.json';
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile($file, $this->_datamapper);
    }
}