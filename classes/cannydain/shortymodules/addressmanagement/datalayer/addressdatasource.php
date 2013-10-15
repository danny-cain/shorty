<?php

namespace CannyDain\ShortyModules\AddressManagement\DataLayer;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Shorty\DataAccess\ShortyDatasource;
use CannyDain\Shorty\Geo\Models\Address;

class AddressDataSource extends ShortyDatasource
{
    public function registerObjects()
    {
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile(dirname(__FILE__).'/datadictionary.json', $this->_datamapper);
    }

    /**
     * @param $userID
     * @return Address[]
     */
    public function loadAddressesByUser($userID)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(Address::OBJECT_TYPE_ADDRESS, array
        (
            'userID = :user'
        ), array
        (
            'user' => $userID
        ));
    }

    /**
     * @param $id
     * @return Address
     */
    public function loadAddress($id)
    {
        return $this->_datamapper->loadObject(Address::OBJECT_TYPE_ADDRESS, array('id' => $id));
    }

    public function saveAddress(Address $address)
    {
        $this->_datamapper->saveObject($address);
    }
}