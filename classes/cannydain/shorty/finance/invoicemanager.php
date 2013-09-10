<?php

namespace CannyDain\Shorty\Finance;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\ECommerce\Basket\BasketHelperInterface;
use CannyDain\Shorty\Finance\Interfaces\InvoiceItemInterface;
use CannyDain\Shorty\Finance\Models\NullInvoice;
use CannyDain\Shorty\Finance\Models\NullInvoiceItem;
use CannyDain\Shorty\Finance\Views\InvoiceView;
use CannyDain\Shorty\Finance\Interfaces\InvoiceInterface;

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
     * @param BasketHelperInterface $basket
     * @return InvoiceInterface
     */
    public function createFromBasket(BasketHelperInterface $basket)
    {
        return null;
    }

    public function saveInvoice(InvoiceInterface $invoice) {}
    public function saveInvoiceItem(InvoiceItemInterface $invoiceItem) {}

    public function createInvoice()
    {
        return new NullInvoice();
    }

    public function createInvoiceItem()
    {
        return new NullInvoiceItem();
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
        $view->setInvoiceItems($this->getItemsByInvoice($invoiceID));
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