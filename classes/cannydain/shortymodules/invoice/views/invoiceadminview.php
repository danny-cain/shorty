<?php

namespace CannyDain\ShortyModules\Invoice\Views;

use CannyDain\Shorty\Consumers\AddressManagerConsumer;
use CannyDain\Shorty\Finance\Interfaces\InvoiceInterface;
use CannyDain\Shorty\Finance\Interfaces\InvoiceItemInterface;
use CannyDain\Shorty\Geo\AddressManager;
use CannyDain\Shorty\Views\ShortyView;

class InvoiceAdminView extends ShortyView implements AddressManagerConsumer
{
    /**
     * @var InvoiceInterface
     */
    protected $_invoice;

    /**
     * @var AddressManager
     */
    protected $_addressManager;

    /**
     * @var InvoiceItemInterface[]
     */
    protected $_items;

    protected function _displayAddresses()
    {
        $billingView = $this->_addressManager->getViewAddressView($this->_invoice->getBillingAddress());
        $deliveryView = $this->_addressManager->getViewAddressView($this->_invoice->getDeliveryAddress());

        $billingView->setTitle('Billing');
        $deliveryView->setTitle('Delivery');

        echo '<div style="display: inline-block; width: 49%; margin-right: 1%; vertical-align: top;">';
            $billingView->display();
        echo '</div>';

        echo '<div style="display: inline-block; width: 49%; vertical-align: top;">';
            $deliveryView->display();
        echo '</div>';
    }

    protected function _displayItem(InvoiceItemInterface $item)
    {
        echo '<tr>';
            echo '<td>'.$item->getName().'</td>';
            echo '<td>'.$item->getQuantity().'</td>';
            echo '<td>&pound;'.number_format($item->getPricePerUnitInPence() / 100, 2).'</td>';
            echo '<td>&pound;'.number_format($item->getLineDiscountInPence() /  100, 2).'</td>';
            echo '<td>&pound;'.number_format($item->getLineTotal() / 100, 2).'</td>';
        echo '</tr>';
    }

    protected function _displayItems()
    {
        $total = 0;
        $discountTotal = 0;
        $subTotal = 0;

        $discountCode = $this->_invoice->getDiscountCode();
        if ($discountCode != '')
            $discountCode = '('.$discountCode.')';

        echo '<table>';
            echo '<tr>';
                echo '<th>Item</th>';
                echo '<th>Quantity</th>';
                echo '<th>Price per Unit</th>';
                echo '<th>Line Discount</th>';
                echo '<th>Line Total</th>';
            echo '</tr>';
        
            foreach ($this->_items as $item)
            {
                $subTotal += $item->getLineTotalWithoutDiscount();
                $discountTotal += $item->getLineDiscountInPence();
                $total += $item->getLineTotal();

                $this->_displayItem($item);
            }

            echo '<tr>';
                echo '<td colspan="4">';
                    echo 'Subtotal';
                echo '</td>';

                echo '<td>';
                    echo '&pound;'.number_format($subTotal / 100, 2);
                echo '</td>';
            echo '</tr>';

            echo '<tr>';
                echo '<td colspan="4">';
                    echo 'Discount '.$discountCode;
                echo '</td>';

                echo '<td>';
                    echo '&pound;'.number_format($discountTotal / 100, 2);
                echo '</td>';
            echo '</tr>';

            echo '<tr>';
                echo '<td colspan="4">';
                    echo 'Total';
                echo '</td>';

                echo '<td>';
                    echo '&pound;'.number_format($total / 100, 2);
                echo '</td>';
            echo '</tr>';
        echo '</table>';
    }

    public function display()
    {
        echo '<h1>Invoice '.$this->_invoice->getID().'</h1>';

        $this->_displayAddresses();
        $this->_displayItems();
    }

    /**
     * @param \CannyDain\Shorty\Finance\Interfaces\InvoiceInterface $invoice
     */
    public function setInvoice($invoice)
    {
        $this->_invoice = $invoice;
    }

    /**
     * @return \CannyDain\Shorty\Finance\Interfaces\InvoiceInterface
     */
    public function getInvoice()
    {
        return $this->_invoice;
    }

    public function setItems($items)
    {
        $this->_items = $items;
    }

    public function getItems()
    {
        return $this->_items;
    }

    public function consumeAddressManager(AddressManager $manager)
    {
        $this->_addressManager = $manager;
    }
}