<?php

namespace CannyDain\ShortyCoreModules\SimpleShop\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\ShortyCoreModules\SimpleShop\Models\SimpleShopProduct;

class ProductListView extends HTMLView
{
    protected $_title = '';
    /**
     * @var SimpleShopProduct[]
     */
    protected $_products = array();

    protected $_createProductURI = '';

    protected $_viewProductLinkTemplate = '';

    public function display()
    {
        echo '<h1>'.$this->_title.'</h1>';

        foreach ($this->_products as $product)
            $this->_displayItem($product);

        if ($this->_createProductURI != '')
        {
            echo '<a href="'.$this->_createProductURI.'">[create product]</a>';
        }
    }

    protected function _displayItem(SimpleShopProduct $item)
    {
        $viewLink = strtr($this->_viewProductLinkTemplate, array('#id#' => $item->getId()));

        echo '<div class="productInfo">';
            echo '<h2>'.$item->getName().'</h2>';
            echo '<p>'.$item->getSummary().'</p>';
            echo '<div>&pound; '.number_format($item->getPrice() / 100, 2).'</div>';
            echo '<div><a href="'.$viewLink.'">[view this product]</a>';
        echo '</div>';
    }

    public function setCreateProductURI($createProductURI)
    {
        $this->_createProductURI = $createProductURI;
    }

    public function getCreateProductURI()
    {
        return $this->_createProductURI;
    }

    public function setProducts($products)
    {
        $this->_products = $products;
    }

    public function getProducts()
    {
        return $this->_products;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setViewProductLinkTemplate($viewProductLinkTemplate)
    {
        $this->_viewProductLinkTemplate = $viewProductLinkTemplate;
    }

    public function getViewProductLinkTemplate()
    {
        return $this->_viewProductLinkTemplate;
    }
}