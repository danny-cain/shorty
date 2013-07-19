<?php

namespace CannyDain\ShortyCoreModules\Payment_Invoice\Models;

class InvoiceModel
{
    const MODEL_CLASS_NAME = __CLASS__;

    const STATUS_IN_PROGRESS = 1;
    const STATUS_TO_BE_SENT = 2;
    const STATUS_SENT = 3;
    const STATUS_PAID = 4;
    const STATUS_CANCELLED = 5;

    protected $_id = 0;
    protected $_name = '';
    protected $_address1 = '';
    protected $_address2 = '';
    protected $_address3 = '';
    protected $_town = '';
    protected $_county = '';
    protected $_country = '';
    protected $_postcode = '';
    protected $_datePlaced = 0;
    protected $_status = self::STATUS_IN_PROGRESS;
    protected $_shippingInPence = 0;
    protected $_discountInPence = 0;

    public function setDiscountInPence($discountInPence)
    {
        $this->_discountInPence = $discountInPence;
    }

    public function getDiscountInPence()
    {
        return $this->_discountInPence;
    }

    public function setShippingInPence($shippingInPence)
    {
        $this->_shippingInPence = $shippingInPence;
    }

    public function getShippingInPence()
    {
        return $this->_shippingInPence;
    }

    public static function getStatusIDToNameMap()
    {
        $ret = array();
        for ($i = 1; $i <= 5; $i ++)
            $ret[$i] = self::getFriendlyNameForStatus($i);

        return $ret;
    }

    public static function getFriendlyNameForStatus($status)
    {
        switch($status)
        {
            case self::STATUS_IN_PROGRESS:
                return 'In Progress';
            case self::STATUS_TO_BE_SENT:
                return 'To Be Sent';
            case self::STATUS_SENT:
                return 'Sent';
            case self::STATUS_PAID:
                return 'Paid';
            case self::STATUS_CANCELLED:
                return 'Cancelled';
        }

        return 'Unknown';
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function setDatePlaced($datePlaced)
    {
        $this->_datePlaced = $datePlaced;
    }

    public function getDatePlaced()
    {
        return $this->_datePlaced;
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