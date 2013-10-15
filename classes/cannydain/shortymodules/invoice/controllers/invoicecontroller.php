<?php

namespace CannyDain\ShortyModules\Invoice\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Shorty\Consumers\AddressManagerConsumer;
use CannyDain\Shorty\Consumers\BasketHelperConsumer;
use CannyDain\Shorty\Consumers\InvoiceManagerConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Shorty\ECommerce\Basket\BasketHelperInterface;
use CannyDain\Shorty\Exceptions\InvalidStateException;
use CannyDain\Shorty\Finance\Interfaces\InvoiceInterface;
use CannyDain\Shorty\Finance\InvoiceManager;
use CannyDain\Shorty\Finance\Models\PaymentProvider;
use CannyDain\Shorty\Geo\AddressManager;
use CannyDain\ShortyModules\Invoice\Views\PayByInvoiceView;

class InvoiceController extends ShortyController implements InvoiceManagerConsumer, BasketHelperConsumer, AddressManagerConsumer
{
    /**
     * @var InvoiceManager
     */
    protected $_invoiceManager;

    /**
     * @var BasketHelperInterface
     */
    protected $_basket;

    /**
     * @var AddressManager
     */
    protected $_addressManager;

    public function SelectBillingAddress()
    {
        $view = $this->_addressManager->getSelectAddressView(new Route(__CLASS__, 'SelectBillingAddress'));
        $view->setTitle('Billing Address');

        if ($view->updateFromPostAndReturnTrueIfPostedAndValid())
        {
            $address = $view->getSelectedAddress();
            $this->_addressManager->saveAddress($address);

            $this->_basket->setBillingAddress($address->getId());

            return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'SelectDeliveryAddress')));
        }

        return $view;
    }

    public function SelectDeliveryAddress()
    {
        $view = $this->_addressManager->getSelectAddressView(new Route(__CLASS__, 'SelectDeliveryAddress'));
        $view->setTitle('Delivery Address');

        if ($view->updateFromPostAndReturnTrueIfPostedAndValid())
        {
            $address = $view->getSelectedAddress();
            $this->_addressManager->saveAddress($address);

            $this->_basket->setDeliveryAddress($address->getId());

            return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'Complete')));
        }

        return $view;
    }

    public function Complete()
    {
        $invoice = $this->_invoiceManager->createFromBasket($this->_basket);;

        $invoice->setStatus(InvoiceInterface::STATUS_TO_BE_INVOICED);
        $this->_invoiceManager->saveInvoice($invoice);
        $this->_basket->emptyBasket();

        return new PayByInvoiceView();
    }

    public static function GetPaymentProvider()
    {
        return new PaymentProvider('Invoice', new Route(__CLASS__, 'SelectBillingAddress'), 'Pay by Invoice');
    }

    public function consumeInvoiceManager(InvoiceManager $manager)
    {
        $this->_invoiceManager = $manager;
    }

    public function consumeBasketHelper(BasketHelperInterface $helper)
    {
        $this->_basket = $helper;
    }

    public function consumeAddressManager(AddressManager $manager)
    {
        $this->_addressManager = $manager;
    }
}