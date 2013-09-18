<?php

namespace CannyDain\ShortyModules\ObjectPermissions\Datasource;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Shorty\DataAccess\ShortyDatasource;
use CannyDain\ShortyModules\ObjectPermissions\Models\PermissionModel;

class ObjectPermissionsDatasource extends ShortyDatasource
{
    public function registerObjects()
    {
        $file = dirname(__FILE__).'/datadictionary.json';
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile($file, $this->_datamapper);
    }

    /**
     * @param $objectGUID
     * @return PermissionModel[]
     */
    public function getAllPermissionsForObject($objectGUID)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(PermissionModel::OBJECT_TYPE_PERMISSION, array
        (
            'object = :object'
        ), array
        (
            'object' => $objectGUID
        ));
    }

    /**
     * @param $consumerGUID
     * @param $objectGUID
     * @return PermissionModel|null
     */
    public function getPermissionModel($consumerGUID, $objectGUID)
    {
        return array_shift($this->_datamapper->getObjectsWithCustomClauses(PermissionModel::OBJECT_TYPE_PERMISSION, array
        (
            'object = :object',
            'consumer = :consumer'
        ), array
        (
            'object' => $objectGUID,
            'consumer' => $consumerGUID
        )));
    }

    public function getOrCreatePermissionModel($consumerGUID, $objectGUID)
    {
        $model = $this->getPermissionModel($consumerGUID, $objectGUID);
        if ($model == null)
        {
            $model = $this->createPermissionModel();
            $model->setConsumerGUID($consumerGUID);
            $model->setObjectGUID($objectGUID);
        }

        return $model;
    }

    public function createPermissionModel()
    {
        $model = new PermissionModel();
        $this->_dependencies->applyDependencies($model);

        return $model;
    }
}