<?php

namespace CannyDain\ShortyCoreModules\SimpleShop\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;

class AddToBasketForm extends HTMLView implements FormHelperConsumer
{
    /**
     * @var FormHelper
     */
    protected $_formHelper;

    protected $_addToBasketURI = '';
    protected $_productID = '';
    protected $_qty = 1;

    public function display()
    {
        $this->_formHelper->startForm($this->_addToBasketURI);
            $this->_formHelper->hiddenField('product', $this->_productID);
            $this->_formHelper->hiddenField('qty', $this->_qty);
            $this->_formHelper->submitButton('Add to Basket');
        $this->_formHelper->endForm();
    }

    public function updateModelFromRequest(Request $request)
    {
        $this->_productID = $request->getParameter('product');
        $this->_qty = $request->getParameter('qty');
    }

    public function setAddToBasketURI($addToBasketURI)
    {
        $this->_addToBasketURI = $addToBasketURI;
    }

    public function getAddToBasketURI()
    {
        return $this->_addToBasketURI;
    }

    public function setProductID($productGUID)
    {
        $this->_productID = $productGUID;
    }

    public function getProductID()
    {
        return $this->_productID;
    }

    public function setQty($qty)
    {
        $this->_qty = $qty;
    }

    public function getQty()
    {
        return $this->_qty;
    }


    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}