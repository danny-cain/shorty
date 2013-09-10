<?php

namespace CannyDain\ShortyModules\ShortyBasket\Views;

use CannyDain\Shorty\Consumers\PaymentManagerConsumer;
use CannyDain\Shorty\Consumers\ProductManagerConsumer;
use CannyDain\Shorty\ECommerce\Products\ProductManager;
use CannyDain\Shorty\Finance\Models\PaymentProvider;
use CannyDain\Shorty\Finance\PaymentManager;
use CannyDain\Shorty\Views\ShortyView;

class BasketView extends ShortyView implements ProductManagerConsumer, PaymentManagerConsumer
{
    /**
     * @var ProductManager
     */
    protected $_productManager;

    /**
     * @var PaymentManager
     */
    protected $_paymentManager;

    protected $_productQuantities = array();

    public function display()
    {
        echo '<h1>Basket</h1>';

        echo '<table class="basket">';
        foreach ($this->_productQuantities as $guid => $qty)
        {
            if ($qty < 1)
                continue;

            $this->_displayProduct($guid, $qty);
        }
        echo '</table>';

        $this->_displayPaymentMethods();
    }

    protected function _displayPaymentMethods()
    {
        echo '<div class="payment">';
            foreach ($this->_paymentManager->getProviders() as $provider)
                $this->_displayPaymentMethod($provider);
        echo '</div>';
    }

    protected function _displayPaymentMethod(PaymentProvider $method)
    {
        echo '<a class="provider" href="'.$this->_router->getURI($method->getCheckoutRoute()).'">';
            echo $method->getCheckoutButtonMarkup();
        echo '</a>';
    }

    protected function _displayProduct($guid, $qty)
    {
        $product = $this->_productManager->getProductInfo($guid);

        if ($product == null)
            return;

        $lineTotal = $product->getPricePerUnitInPence() * $qty;

        echo '<tr>';
            echo '<td>'.$product->getName().'</td>';
            echo '<td>'.$product->getDescription().'</td>';
            echo '<td>'.$qty.'</td>';
            echo '<td>*</td>';
            echo '<td>&pound;'.number_format($product->getPricePerUnitInPence() / 100, 2).'</td>';
            echo '<td>&pound;'.number_format($lineTotal / 100, 2).'</td>';
        echo '</tr>';
    }

    public function setProductQuantities($guidToQtyMap)
    {
        $this->_productQuantities = $guidToQtyMap;
    }

    public function consumeProductManager(ProductManager $manager)
    {
        $this->_productManager = $manager;
    }

    public function consumePaymentManager(PaymentManager $manager)
    {
        $this->_paymentManager = $manager;
    }
}