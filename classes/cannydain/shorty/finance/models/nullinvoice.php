<?php

namespace CannyDain\Shorty\Finance\Models;

use CannyDain\Shorty\Finance\Interfaces\InvoiceInterface;

class NullInvoice implements InvoiceInterface
{
    public function setStatus($status)
    {

    }

    public function getStatus()
    {
        return self::STATUS_TO_BE_INVOICED;
    }

    public function setDiscountCode($code)
    {

    }

    public function getDiscountCode()
    {
        return '';
    }

    /**
     * @param int $addressID
     * @return void
     */
    public function setDeliveryAddress($addressID)
    {

    }

    /**
     * @return int
     */
    public function getDeliveryAddress()
    {
        return 0;
    }

    /**
     * @param int $addressID
     * @return void
     */
    public function setBillingAddress($addressID)
    {

    }

    /**
     * @return int
     */
    public function getBillingAddress()
    {
        return 0;
    }

    /**
     * @return int
     */
    public function getID()
    {
        return 0;
    }
}