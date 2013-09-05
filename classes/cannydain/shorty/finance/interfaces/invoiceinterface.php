<?php

namespace CannyDain\Shorty\Finances\Interfaces;

interface InvoiceInterface
{
    const STATUS_TO_BE_INVOICED = 0;
    const STATUS_INVOICED = 1;
    const STATUS_PAID = 2;
    const STATUS_CANCELLED = 3;

    public function setStatus($status);
    public function getStatus();

    public function setDiscountCode($code);
    public function getDiscountCode();

    /**
     * @param int $addressID
     * @return void
     */
    public function setDeliveryAddress($addressID);

    /**
     * @return int
     */
    public function getDeliveryAddress();

    /**
     * @param int $addressID
     * @return void
     */
    public function setBillingAddress($addressID);

    /**
     * @return int
     */
    public function getBillingAddress();

    /**
     * @return int
     */
    public function getID();
}