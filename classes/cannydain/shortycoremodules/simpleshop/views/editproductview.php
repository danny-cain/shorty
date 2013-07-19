<?php

namespace CannyDain\ShortyCoreModules\SimpleShop\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\SimpleShop\Models\SimpleShopProduct;

class EditProductView extends HTMLView implements FormHelperConsumer
{
    /**
     * @var SimpleShopProduct
     */
    protected $_product;
    protected $_saveURI = '';

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    public function display()
    {
        echo '<h1>Add/Edit Product</h1>';

        $this->_formHelper->startForm($this->_saveURI);
            $this->_formHelper->editText('name', 'Name', $this->_product->getName(), 'The name of this product');
            $this->_formHelper->editText('price', 'Price', $this->_product->getPrice(), 'The price of this product (in pence, before tax)');
            $this->_formHelper->editText('weight', 'Weight', $this->_product->getWeight(), 'The weight of this product (in lbs)');
            $this->_formHelper->editText('stock', 'Stock', $this->_product->getStockLevel(), 'The current stock level of this product (-1 for infinite)');
            $this->_formHelper->editText('tax', 'Tax Rate', $this->_product->getTaxRate(), 'The tax that will be charged on this product');
            $this->_formHelper->editRichText('summary', 'Summary', $this->_product->getSummary(), 'A brief summary/description of this product');
            $this->_formHelper->submitButton('Save');
        $this->_formHelper->endForm();
    }

    public function updateFromRequest(Request $request)
    {
        $this->_product->setName($request->getParameter('name'));
        $this->_product->setPrice($request->getParameter('price'));
        $this->_product->setWeight($request->getParameter('weight'));
        $this->_product->setStockLevel($request->getParameter('stock'));
        $this->_product->setSummary($request->getParameter('summary'));
        $this->_product->setTaxRate(floatval($request->getParameter('tax')));
    }

    /**
     * @param \CannyDain\ShortyCoreModules\SimpleShop\Models\SimpleShopProduct $product
     */
    public function setProduct($product)
    {
        $this->_product = $product;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\SimpleShop\Models\SimpleShopProduct
     */
    public function getProduct()
    {
        return $this->_product;
    }

    public function setSaveURI($saveURI)
    {
        $this->_saveURI = $saveURI;
    }

    public function getSaveURI()
    {
        return $this->_saveURI;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}