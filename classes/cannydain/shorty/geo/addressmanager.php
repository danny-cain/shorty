<?php

namespace CannyDain\Shorty\Geo;

use CannyDain\Shorty\Geo\Models\Address;

class AddressManager
{
    public function saveAddress(Address $address)
    {

    }

    /**
     * @param $userID
     * @return Address[]
     */
    public function getAddressByUser($userID)
    {
        return array();
    }

    /**
     * @param $id
     * @return Address
     */
    public function getAddressByID($id)
    {
        return null;
    }
}