<?php

namespace CannyDain\ShortyModules\Invoice\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\InvoiceManagerConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Shorty\Exceptions\InvalidStateException;
use CannyDain\Shorty\Finance\Interfaces\InvoiceInterface;
use CannyDain\Shorty\Finance\InvoiceManager;
use CannyDain\Shorty\Finance\Models\PaymentProvider;
use CannyDain\ShortyModules\Invoice\Views\PayByInvoiceView;

class InvoiceController extends ShortyController implements InvoiceManagerConsumer
{
    /**
     * @var InvoiceManager
     */
    protected $_invoiceManager;

    public function Pay($invoiceID)
    {
        $invoice = $this->_invoiceManager->getInvoiceByID($invoiceID);
        if ($invoice->getStatus() != InvoiceInterface::STATUS_TO_BE_INVOICED)
            throw new InvalidStateException(__CLASS__, 'Invoice is not to be paid');

        $invoice->setStatus(InvoiceInterface::STATUS_INVOICED);
        $this->_invoiceManager->saveInvoice($invoice);

        return new PayByInvoiceView();
    }

    public static function GetPaymentProvider()
    {
        return new PaymentProvider('Invoice', new Route(__CLASS__, 'Pay', array('#invoiceID#')), 'Pay by Invoice');
    }

    public function consumeInvoiceManager(InvoiceManager $manager)
    {
        $this->_invoiceManager = $manager;
    }
}