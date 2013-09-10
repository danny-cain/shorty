<?php

namespace CannyDain\ShortyModules\Invoice\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\BasketHelperConsumer;
use CannyDain\Shorty\Consumers\InvoiceManagerConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Shorty\ECommerce\Basket\BasketHelperInterface;
use CannyDain\Shorty\Exceptions\InvalidStateException;
use CannyDain\Shorty\Finance\Interfaces\InvoiceInterface;
use CannyDain\Shorty\Finance\InvoiceManager;
use CannyDain\Shorty\Finance\Models\PaymentProvider;
use CannyDain\ShortyModules\Invoice\Views\PayByInvoiceView;

class InvoiceController extends ShortyController implements InvoiceManagerConsumer, BasketHelperConsumer
{
    /**
     * @var InvoiceManager
     */
    protected $_invoiceManager;

    /**
     * @var BasketHelperInterface
     */
    protected $_basket;

    public function Pay()
    {
        $invoice = $this->_invoiceManager->createFromBasket($this->_basket);;

        $invoice->setStatus(InvoiceInterface::STATUS_INVOICED);
        $this->_invoiceManager->saveInvoice($invoice);
        $this->_basket->emptyBasket();

        return new PayByInvoiceView();
    }

    public static function GetPaymentProvider()
    {
        return new PaymentProvider('Invoice', new Route(__CLASS__, 'Pay'), 'Pay by Invoice');
    }

    public function consumeInvoiceManager(InvoiceManager $manager)
    {
        $this->_invoiceManager = $manager;
    }

    public function consumeBasketHelper(BasketHelperInterface $helper)
    {
        $this->_basket = $helper;
    }
}