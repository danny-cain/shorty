<?php

namespace CannyDain\Shorty\Geo\Models;

class Address
{
    protected $_id = 0;
    protected $_name = '';
    protected $_address1 = '';
    protected $_address2 = '';
    protected $_address3 = '';
    protected $_town = '';
    protected $_county = '';
    protected $_country = '';
    protected $_postcode = '';
    protected $_userID = 0;

    public function setUserID($userID)
    {
        $this->_userID = $userID;
    }

    public function getUserID()
    {
        return $this->_userID;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setAddress1($address1)
    {
        $this->_address1 = $address1;
    }

    public function getAddress1()
    {
        return $this->_address1;
    }

    public function setAddress2($address2)
    {
        $this->_address2 = $address2;
    }

    public function getAddress2()
    {
        return $this->_address2;
    }

    public function setAddress3($address3)
    {
        $this->_address3 = $address3;
    }

    public function getAddress3()
    {
        return $this->_address3;
    }

    public function setCountry($country)
    {
        $this->_country = $country;
    }

    public function getCountry()
    {
        return $this->_country;
    }

    public function setCounty($county)
    {
        $this->_county = $county;
    }

    public function getCounty()
    {
        return $this->_county;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setPostcode($postcode)
    {
        $this->_postcode = $postcode;
    }

    public function getPostcode()
    {
        return $this->_postcode;
    }

    public function setTown($town)
    {
        $this->_town = $town;
    }

    public function getTown()
    {
        return $this->_town;
    }
}