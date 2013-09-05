<?php

namespace CannyDain\Shorty\Finance;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Finance\Interfaces\InvoiceItemInterface;
use CannyDain\Shorty\Finance\Views\InvoiceView;
use CannyDain\Shorty\Finances\Interfaces\InvoiceInterface;

class InvoiceManager
{
    /**
     * @param $id
     * @return InvoiceInterface
     */
    public function getInvoiceByID($id)
    {
        return null;
    }

    /**
     * @param $invoiceID
     * @param Route $removeItemRoute
     * @return InvoiceView
     */
    public function getInvoiceView($invoiceID, $removeItemRoute)
    {
        $view = new InvoiceView();

        $this->updateItemDiscounts($invoiceID);
        $view->setInvoice($this->getInvoiceByID($invoiceID));
        $view->setInvoiceItems($invoiceID);
        $view->setRemoveItemRoute($removeItemRoute);

        return $view;
    }

    /**
     * Returns the invoice items linked to the specified invoice
     * @param $invoiceID
     * @return InvoiceItemInterface[]
     */
    public function getItemsByInvoice($invoiceID)
    {
        return array();
    }

    /**
     * Updates discounts for the items linked to the specified invoice
     * @param $invoiceID
     */
    public function updateItemDiscounts($invoiceID)
    {

    }
}