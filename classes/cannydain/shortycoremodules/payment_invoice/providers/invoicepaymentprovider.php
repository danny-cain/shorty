<?php

namespace CannyDain\ShortyCoreModules\Payment_Invoice\Providers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\ECommerce\PaymentProviders\PaymentProviderInterface;
use CannyDain\ShortyCoreModules\Payment_Invoice\Controllers\InvoicePaymentController;
use CannyDain\ShortyCoreModules\Payment_Invoice\Views\CheckoutButtonView;

class InvoicePaymentProvider implements PaymentProviderInterface, DependencyConsumer, RouterConsumer
{
    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @return ViewInterface
     */
    public function getCheckoutButton()
    {
        $view = new CheckoutButtonView();
        $this->_dependencies->applyDependencies($view);
        $view->setPaymentURI($this->_router->getURI(new Route(InvoicePaymentController::CONTROLLER_CLASS_NAME, 'Pay')));

        return $view;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }
}