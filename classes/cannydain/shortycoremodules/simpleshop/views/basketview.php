<?php

namespace CannyDain\ShortyCoreModules\SimpleShop\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\ECommerce\Models\ShortyBasketModel;
use CannyDain\Shorty\ECommerce\PaymentProviders\PaymentProviderInterface;

class BasketView extends HTMLView
{
    /**
     * @var ShortyBasketModel
     */
    protected $_basket;

    /**
     * @var ViewInterface
     */
    protected $_discountCodeForm;

    /**
     * @var PaymentProviderInterface[]
     */
    protected $_paymentProviders = array();

    public function display()
    {
        echo '<h1>Basket</h1>';

        if ($this->_basket == null || count($this->_basket->getItems()) == 0)
        {
            echo '<p>You have no items in your basket.</p>';
            return;
        }

        $tax = 0;
        $total = 0;
        echo '<table>';
            foreach ($this->_basket->getItems() as $item)
            {
                $linePrice = $item->getQty() * $item->getPriceInPencePerUnit();
                $total += $linePrice;

                $tax += ($item->getTaxRate() * $linePrice);

                echo '<tr>';
                    echo '<th>'.$item->getName().'</th>';
                    echo '<td>&pound;'.number_format($item->getPriceInPencePerUnit() / 100, 2).'</td>';
                    echo '<td>'.$item->getQty().'</td>';
                    echo '<td>&pound;'.number_format($linePrice / 100, 2).'</td>';
                echo '</tr>';
            }

            echo '<tr>';
                echo '<td colspan="3" align="right">';
                    echo 'Subtotal';
                echo '</td>';

                echo '<td>&pound;'.number_format($total / 100, 2).'</td>';
            echo '</tr>';

            echo '<tr>';
                echo '<td colspan="3" align="right">';
                    echo 'Tax';
                echo '</td>';

                echo '<td>&pound;'.number_format($tax / 100, 2).'</td>';
            echo '</tr>';
            $total += $tax;

            echo '<tr>';
                echo '<td colspan="3" align="right">';
                    echo 'Shipping';
                echo '</td>';

                echo '<td>&pound;'.number_format($this->_basket->getShippingInPence() / 100, 2).'</td>';
            echo '</tr>';
            $total += $this->_basket->getShippingInPence();

            echo '<tr>';
                echo '<td colspan="3" align="right">';
                    echo 'Discount';
                echo '</td>';

                echo '<td>&pound;'.number_format($this->_basket->getDiscountInPence() / 100, 2).'</td>';
            echo '</tr>';
            $total -= $this->_basket->getDiscountInPence();

            echo '<tr>';
                echo '<td colspan="3" align="right">';
                    echo 'Total';
                echo '</td>';

                echo '<td>&pound;'.number_format($total / 100, 2).'</td>';
            echo '</tr>';
        echo '</table>';

        echo '<div style="text-align: right;">';
            echo '<div style="width: 40%;">';
                if ($this->_discountCodeForm != null)
                    $this->_discountCodeForm->display();
            echo '</div>';
        echo '</div>';

        foreach ($this->_paymentProviders as $provider)
            $provider->getCheckoutButton()->display();
    }

    /**
     * @param \CannyDain\Lib\UI\Views\ViewInterface $discountCodeForm
     */
    public function setDiscountCodeForm($discountCodeForm)
    {
        $this->_discountCodeForm = $discountCodeForm;
    }

    /**
     * @return \CannyDain\Lib\UI\Views\ViewInterface
     */
    public function getDiscountCodeForm()
    {
        return $this->_discountCodeForm;
    }

    /**
     * @param \CannyDain\Shorty\ECommerce\Models\ShortyBasketModel $basket
     */
    public function setBasket($basket)
    {
        $this->_basket = $basket;
    }

    /**
     * @return \CannyDain\Shorty\ECommerce\Models\ShortyBasketModel
     */
    public function getBasket()
    {
        return $this->_basket;
    }

    public function setPaymentProviders($paymentProviders)
    {
        $this->_paymentProviders = $paymentProviders;
    }

    public function getPaymentProviders()
    {
        return $this->_paymentProviders;
    }
}