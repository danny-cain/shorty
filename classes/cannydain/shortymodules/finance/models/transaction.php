<?php

namespace CannyDain\ShortyModules\Finance\Models;

use CannyDain\Shorty\Models\ShortyModel;

class Transaction extends ShortyModel
{
    const OBJECT_TYPE_TRANSACTION = __CLASS__;

    const TRANS_TYPE_TRANSACTION = 't';
    const TRANS_TYPE_BALANCE= 'b';

    protected $_id = 0;
    protected $_transactionDateTime = 0;
    protected $_sourceAccount = 0;
    protected $_destinationAccount = 0;
    protected $_amount = 0;
    protected $_description = '';
    protected $_type = self::TRANS_TYPE_TRANSACTION;

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        return array();
    }

    public function setTransactionDateTime($transDate)
    {
        $this->_transactionDateTime = $transDate;
    }

    public function getTransactionDateTime()
    {
        return $this->_transactionDateTime;
    }

    public function setAmount($amount)
    {
        $this->_amount = $amount;
    }

    public function getAmount()
    {
        return $this->_amount;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function setDestinationAccount($destinationAccount)
    {
        $this->_destinationAccount = $destinationAccount;
    }

    public function getDestinationAccount()
    {
        return $this->_destinationAccount;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setSourceAccount($sourceAccount)
    {
        $this->_sourceAccount = $sourceAccount;
    }

    public function getSourceAccount()
    {
        return $this->_sourceAccount;
    }

    public function setType($type)
    {
        $this->_type = $type;
    }

    public function getType()
    {
        return $this->_type;
    }
}