<?php

namespace CannyDain\ShortyCoreModules\Payment_Invoice\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Utils\Date\DateFormatManager;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\DateTimeConsumer;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\Payment_Invoice\Models\InvoiceItemModel;
use CannyDain\ShortyCoreModules\Payment_Invoice\Models\InvoiceModel;

class InvoiceView extends HTMLView implements DateTimeConsumer, FormHelperConsumer
{
    /**
     * @var InvoiceModel
     */
    protected $_invoice;

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    /**
     * @var string
     */
    protected $_saveURI = '';

    protected $_isPrintView = false;

    /**
     * @var DateFormatManager
     */
    protected $_dates;

    /**
     * @var InvoiceItemModel[]
     */
    protected $_items = array();

    public function isPrintableView()
    {
        return $this->_isPrintView;
    }

    public function setIsPrintView($isPrintView)
    {
        $this->_isPrintView = $isPrintView;
    }

    protected function _displayUpdateForm()
    {
        if ($this->_isPrintView)
            return;

        $this->_formHelper->startForm($this->_saveURI);
            $this->_formHelper->select('status', 'Status', InvoiceModel::getStatusIDToNameMap(), $this->_invoice->getStatus(), 'The status of this invoice');
            $this->_formHelper->editText('shipping', 'Shipping (&pound;)', number_format($this->_invoice->getShippingInPence() / 100, 2), 'Shipping charges for this invoice');
            $this->_formHelper->editText('discount', 'Discount (&pound;)', number_format($this->_invoice->getDiscountInPence() / 100, 2), 'The discount applied to this invoice (note, this is applied to the post tax total)');
            $this->_formHelper->submitButton('Update');
        $this->_formHelper->endForm();
    }

    public function updateFromRequest(Request $request)
    {
        $this->_invoice->setStatus($request->getParameter('status'));
        $this->_invoice->setShippingInPence(intval($request->getParameter('shipping') * 100));
        $this->_invoice->setDiscountInPence(intval($request->getParameter('discount') * 100));
    }

    protected function _displayCompanyAddress()
    {
        echo '<div style="text-align: right;">';
            echo '<div>Danny Cain</div>';
            echo '<div>23 Temple Road</div>';
            echo '<div>Cowley</div>';
            echo '<div>Oxford</div>';
            echo '<div>Oxfordshire</div>';
            echo '<div>OX4 2ET</div>';
            echo '<div>T: 07720904203</div>';
            echo '<div>E: danny@dannycain.com</div>';
        echo '</div>';
    }

    protected function _getItemsByTaxRate()
    {
        $ret = array();

        foreach ($this->_items as $item)
        {
            $key = "".$item->getTaxRate();
            $ret[$key][] = $item;
        }

        return $ret;
    }

    protected function _displayInvoiceDate()
    {
        echo '<div style="text-align: right;">';
            echo 'Date: '.$this->_dates->getFormattedDate($this->_invoice->getDatePlaced());
        echo '</div>';
    }

    protected function _displayRecipientAddress()
    {
        echo '<div style="text-align: left;">';
            echo '<div>'.$this->_invoice->getName().'</div>';
            echo '<div>'.$this->_invoice->getAddress1().'</div>';
            echo '<div>'.$this->_invoice->getAddress2().'</div>';
            echo '<div>'.$this->_invoice->getAddress3().'</div>';
            echo '<div>'.$this->_invoice->getTown().'</div>';
            echo '<div>'.$this->_invoice->getCounty().'</div>';
            echo '<div>'.$this->_invoice->getPostcode().'</div>';
            echo '<div>'.$this->_invoice->getCountry().'</div>';
        echo '</div>';
    }

    public function display()
    {
        $this->_displayUpdateForm();

        $this->_displayCompanyAddress();

        echo '<div style="text-align: center; font-weight: bold; font-size: 1.2em;">';
            echo 'Invoice Number '.str_pad($this->_invoice->getId(), 3, '0', STR_PAD_LEFT);
        echo '</div>';

        $this->_displayInvoiceDate();
        $this->_displayRecipientAddress();
        $this->_displayItems();
        $this->_displayTerms();
    }

    /**
     * @param string $saveURI
     */
    public function setSaveURI($saveURI)
    {
        $this->_saveURI = $saveURI;
    }

    /**
     * @return string
     */
    public function getSaveURI()
    {
        return $this->_saveURI;
    }

    protected function _displayTerms()
    {
        echo '<div style="font-size: 0.8em; font-style: italic;">';
            echo 'Payment must be received within 30 days';
        echo '</div>';
    }

    protected function _displayItems()
    {
        echo '<table style="width: 100%;">';
            echo '<tr>';
                echo '<th style="width: 70%; ">Item</th>';
                echo '<th style="width: 10%; ">Price per Unit</th>';
                echo '<th style="width: 10%; ">Quantity</th>';
                echo '<th style="width: 10%; ">Total</th>';
            echo '</tr>';

            $itemsSortedByTaxRate = $this->_getItemsByTaxRate();
            $taxRates = array_keys($itemsSortedByTaxRate);
            sort($taxRates);

            $total = 0;
            foreach ($taxRates as $key)
            {
                $rate = floatval($key);
                $rateTotal = 0;
                /**
                 * @var InvoiceItemModel $item
                 */
                foreach ($itemsSortedByTaxRate[$key] as $item)
                {
                    $rateTotal += $item->getLineTotalInPence();
                    $this->_displayItem($item);
                }
                //$this->_displaySubtotal($rateTotal);
                $tax = $this->_calculateTax($rateTotal, $rate);

                if ($tax > 0)
                {
                    $this->_displayTax($tax, $rate);
                }

                $rateTotal += $tax;
                $total += $rateTotal;
            }

            if ($this->_invoice->getDiscountInPence() > 0)
            {
                echo '<tr>';
                    echo '<td colspan="3" align="right">';
                        echo '<strong>Discount</strong>';
                    echo '</td>';

                    echo '<td style="text-align: right;">';
                        echo '-&pound;'.number_format($this->_invoice->getDiscountInPence() / 100, 2);
                    echo '</td>';
                echo '</tr>';
                $total -= $this->_invoice->getDiscountInPence();
            }

            if ($this->_invoice->getShippingInPence() > 0)
            {
                echo '<tr>';
                    echo '<td colspan="3" align="right">';
                        echo '<strong>Shipping</strong>';
                    echo '</td>';

                    echo '<td style="text-align: right;">';
                        echo '&pound;'.number_format($this->_invoice->getShippingInPence() / 100, 2);
                    echo '</td>';
                echo '</tr>';
                $total += $this->_invoice->getShippingInPence();
            }

            echo '<tr>';
                echo '<td colspan="3" align="right">';
                    echo '<strong>Total</strong>';
                echo '</td>';

                echo '<td style="text-align: right;">';
                    echo '&pound;'.number_format($total / 100, 2);
                echo '</td>';
            echo '</tr>';
        echo '</table>';
    }

    protected function _calculateTax($taxableAmount, $taxRate)
    {
        return $taxableAmount * $taxRate;
    }

    protected function _displayTax($taxInPence, $taxRate)
    {
        $taxCaption = number_format($taxRate * 100, 2);
            echo '<tr>';
                echo '<td colspan="3" style="text-align: right; padding: 0 5px;" >Tax @ '.$taxCaption.'%</td>';
                echo '<td style="text-align: right; ">&pound;'.number_format($taxInPence / 100, 2).'</td>';
            echo '</tr>';
    }

    protected function _displaySubtotal($totalInPence)
    {
            echo '<tr>';
                echo '<td colspan="3" style="font-style: italic; text-align: right; padding: 0 5px;" >Subtotal</td>';
                echo '<td style="text-align: right; font-weight: bold; text-decoration: underline; "><div style="display: inline-block; border-bottom: 1px solid black; text-decoration: underline; padding-bottom: 2px;" >&pound;'.number_format($totalInPence / 100, 2).'</div></td>';
            echo '</tr>';
    }

    protected function _displayItem(InvoiceItemModel $item)
    {
            echo '<tr>';
                echo '<td style="text-align: right; padding: 0 5px;" >'.$item->getItemName().'</td>';
                echo '<td style="text-align: center; ">&pound;'.number_format($item->getPricePerUnit() / 100, 2).'</td>';
                echo '<td style="text-align: center; ">'.$item->getQty().'</td>';
                echo '<td style="text-align: right; ">&pound;'.number_format($item->getLineTotalInPence() / 100, 2).'</td>';
            echo '</tr>';
    }

    public function setItems($items)
    {
        $this->_items = $items;
    }

    public function getItems()
    {
        return $this->_items;
    }

    /**
     * @param \CannyDain\ShortyCoreModules\Payment_Invoice\Models\InvoiceModel $invoice
     */
    public function setInvoice($invoice)
    {
        $this->_invoice = $invoice;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\Payment_Invoice\Models\InvoiceModel
     */
    public function getInvoice()
    {
        return $this->_invoice;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDateTimeManager(DateFormatManager $dependency)
    {
        $this->_dates = $dependency;
    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}