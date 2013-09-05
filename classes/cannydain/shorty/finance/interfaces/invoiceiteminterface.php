<?php

namespace CannyDain\Shorty\Finance\Interfaces;

interface InvoiceItemInterface
{
    const STATUS_TO_BE_PROCESSED = 0;
    const STATUS_READY_TO_DESPATCH = 1;
    const STATUS_DESPATCHED = 2;
    const STATUS_DELIVERED = 3;

    public function setStatus($status);
    public function getStatus();

    public function setLineDiscountInPence($discount);
    public function getLineDiscountInPence();

    public function setTaxRate($taxRate);
    public function getTaxRate();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return int
     */
    public function getPricePerUnitInPence();

    /**
     * @return int
     */
    public function getQuantity();

    /**
     * @return int
     */
    public function getInvoiceID();
}