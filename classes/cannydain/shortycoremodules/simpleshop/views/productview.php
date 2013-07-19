<?php

namespace CannyDain\ShortyCoreModules\SimpleShop\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\ShortyCoreModules\SimpleShop\Models\SimpleShopProduct;

class ProductView extends HTMLView
{
    /**
     * @var SimpleShopProduct
     */
    protected $_product;

    /**
     * @var ViewInterface
     */
    protected $_addToBasketForm;

    public function display()
    {
        echo '<h1>'.$this->_product->getName().'</h1>';
        echo '<p>'.$this->_product->getSummary().'</p>';
        echo '<p>&pound;'.number_format($this->_product->getPrice() / 100, 2).'</p>';

        if ($this->_addToBasketForm != null)
            $this->_addToBasketForm->display();
    }

    /**
     * @param \CannyDain\Lib\UI\Views\ViewInterface $addToBasketForm
     */
    public function setAddToBasketForm($addToBasketForm)
    {
        $this->_addToBasketForm = $addToBasketForm;
    }

    /**
     * @return \CannyDain\Lib\UI\Views\ViewInterface
     */
    public function getAddToBasketForm()
    {
        return $this->_addToBasketForm;
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
}