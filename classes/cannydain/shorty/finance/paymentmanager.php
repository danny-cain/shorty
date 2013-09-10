<?php

namespace CannyDain\Shorty\Finance;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\InvoiceManagerConsumer;
use CannyDain\Shorty\Finance\InvoiceManager;
use CannyDain\Shorty\Finance\Models\PaymentProvider;
use CannyDain\Shorty\Finance\Views\BasketView;

class PaymentManager implements InvoiceManagerConsumer, DependencyConsumer
{
    /**
     * @var InvoiceManager
     */
    protected $_invoiceManager;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var PaymentProvider[]
     */
    protected $_providers = array();

    public function registerProvider(PaymentProvider $provider)
    {
        $this->_providers[] = $provider;
    }

    public function getProviders()
    {
        return $this->_providers;
    }

    /**
     * @param $invoiceID
     * @param Route $continueShoppingRoute
     * @param Route $removeItemRoute
     * @return ViewInterface
     */
    public function getBasketView($invoiceID, $continueShoppingRoute = null, $removeItemRoute = null)
    {
        $view = new BasketView();

        $invoiceView = $this->_invoiceManager->getInvoiceView($invoiceID, $removeItemRoute);
        $this->_dependencies->applyDependencies($invoiceView);

        $view->setContinueShoppingRoute($continueShoppingRoute);
        $view->setInvoiceID($invoiceID);
        $view->setPaymentProviders($this->_providers);
        $view->setInvoiceView($invoiceView);
        $this->_dependencies->applyDependencies($view);

        return $view;
    }

    public function consumeDependencies(DependencyInjector $dependencies)
    {
        $this->_dependencies = $dependencies;
    }

    public function consumeInvoiceManager(InvoiceManager $manager)
    {
        $this->_invoiceManager = $manager;
    }
}