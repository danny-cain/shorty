<?php

namespace CannyDain\Shorty\Finance\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Finance\Interfaces\InvoiceItemInterface;
use CannyDain\Shorty\Finances\Interfaces\InvoiceInterface;
use CannyDain\Shorty\Views\ShortyView;

class InvoiceView extends ShortyView
{
    /**
     * @var Route
     */
    protected $_removeItemRoute;

    /**
     * @var InvoiceInterface
     */
    protected $_invoice;

    /**
     * @var InvoiceItemInterface[]
     */
    protected $_invoiceItems = array();

    public function display()
    {
        $this->_displayAddresses();
        $this->_displayItems();
    }

    protected function _displayAddresses() {}

    protected function _displayItems()
    {
        $totalsByTaxRate = array();
        $total = 0;

        echo '<table>';
            foreach ($this->_invoiceItems as $item)
            {
                $lineTotal = $this->_displayItemAndReturnLineTotalInPence($item);
                $total += $lineTotal;

                if (!isset($totalsByTaxRate[$item->getTaxRate()]))
                    $totalsByTaxRate[$item->getTaxRate()] = 0;

                $totalsByTaxRate[$item->getTaxRate()] += $lineTotal;
            }

            $this->_displaySubtotal($total);
            $taxTotal = $this->_displayTaxAndReturnTotal($totalsByTaxRate);
            $this->_displayGrandTotal($total + $taxTotal);
        echo '</table>';
    }

    protected function _displayGrandTotal($amountInPence)
    {
        echo '<tr>';
                echo '<td colspan="3" align="right">';
                    echo 'Grand Total';
                echo '</td>';

                echo '<td>';
                    echo number_format($amountInPence / 100, 2);
                echo '</td>';
            echo '</tr>';
    }

    protected function _displayItemAndReturnLineTotalInPence(InvoiceItemInterface $item)
    {
        $lineTotal = $item->getPricePerUnitInPence() * $item->getQuantity();

        echo '<tr>';
            echo '<td>'.$item->getName().'</td>';
            echo '<td>&pound;'.number_format($item->getPricePerUnitInPence() / 100, 2).'</td>';
            echo '<td>'.$item->getQuantity().'</td>';
            echo '<td>'.number_format($lineTotal / 100, 2).'</td>';
        echo '</tr>';

        if ($item->getLineDiscountInPence() < 1)
            return $lineTotal;

        echo '<tr>';
            echo '<td>Line Discount</td>';
            echo '<td>&pound;'.number_format($item->getLineDiscountInPence() / 100, 2).'</td>';
            echo '<td>1</td>';
            echo '<td>-'.number_format($item->getLineDiscountInPence() / 100, 2).'</td>';
        echo '</tr>';

        return $lineTotal - $item->getLineDiscountInPence();
    }

    protected function _displaySubtotal($amountInPence)
    {
        echo '<tr>';
            echo '<td colspan="3" align="right">';
                echo 'Subtotal';
            echo '</td>';

            echo '<td>';
                echo '&pound;'.number_format($amountInPence / 100, 2);
            echo '</td>';
        echo '</tr>';
    }

    protected function _displayTaxAndReturnTotal($totalsByTaxrate)
    {
        $taxRates = array_keys($totalsByTaxrate);
        sort($taxRates);

        $totalTax = 0;

        foreach ($taxRates as $rate)
        {
            if ($rate <= 0)
                continue;

            $totalGoodsValue = $totalsByTaxrate[$rate];
            $tax = $totalGoodsValue * $rate;
            $totalTax += $tax;

            echo '<tr>';
                echo '<td colspan="3" align="right">';
                    echo 'Tax @ '.number_format($rate, 2).'%';
                echo '</td>';

                echo '<td>';
                    echo number_format($tax, 2);
                echo '</td>';
            echo '</tr>';
        }

        return $totalTax;
    }

    /**
     * @param \CannyDain\Shorty\Finances\Interfaces\InvoiceInterface $invoice
     */
    public function setInvoice($invoice)
    {
        $this->_invoice = $invoice;
    }

    /**
     * @return \CannyDain\Shorty\Finances\Interfaces\InvoiceInterface
     */
    public function getInvoice()
    {
        return $this->_invoice;
    }

    public function setInvoiceItems($invoiceItems)
    {
        $this->_invoiceItems = $invoiceItems;
    }

    public function getInvoiceItems()
    {
        return $this->_invoiceItems;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $removeItemRoute
     */
    public function setRemoveItemRoute($removeItemRoute)
    {
        $this->_removeItemRoute = $removeItemRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getRemoveItemRoute()
    {
        return $this->_removeItemRoute;
    }
}