<?php

namespace CannyDain\Shorty\Finance\Models;

use CannyDain\Shorty\Finance\Interfaces\InvoiceItemInterface;

class NullInvoiceItem implements InvoiceItemInterface
{
    public function setStatus($status)
    {
        // TODO: Implement setStatus() method.
    }

    public function getLineTotal()
    {
        // TODO: Implement getLineTotal() method.
    }

    public function getLineTotalWithoutDiscount()
    {
        // TODO: Implement getLineTotal() method.
    }


    public function getStatus()
    {
        // TODO: Implement getStatus() method.
    }

    public function setLineDiscountInPence($discount)
    {
        // TODO: Implement setLineDiscountInPence() method.
    }

    public function getLineDiscountInPence()
    {
        // TODO: Implement getLineDiscountInPence() method.
    }

    public function setTaxRate($taxRate)
    {
        // TODO: Implement setTaxRate() method.
    }

    public function setName($name)
    {
        // TODO: Implement setName() method.
    }

    public function setPricePerUnitInPence($price)
    {
        // TODO: Implement setPricePerUnitInPence() method.
    }

    public function setQuantity($qty)
    {
        // TODO: Implement setQuantity() method.
    }

    public function setInvoiceID($id)
    {
        // TODO: Implement setInvoiceID() method.
    }

    public function getTaxRate()
    {
        return 0;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }

    /**
     * @return int
     */
    public function getPricePerUnitInPence()
    {
        return 0;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return 0;
    }

    /**
     * @return int
     */
    public function getInvoiceID()
    {
        return 0;
    }
}