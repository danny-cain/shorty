<?php

namespace CannyDain\ShortyModules\SimpleShop\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Views\ShortyView;
use CannyDain\ShortyModules\SimpleShop\Models\Product;

class ProductListView extends ShortyView
{
    protected $_title = '';

    /**
     * @var Product[]
     */
    protected $_products = array();

    /**
     * @var Route
     */
    protected $_viewProductRouteTemplate;

    public function display()
    {
        echo '<h1>'.$this->_title.'</h1>';

        $this->_preProductDisplay();

        foreach ($this->_products as $product)
            $this->_displayProduct($product);

        $this->_postProductDisplay();
    }

    protected function _preProductDisplay() {}

    protected function _postProductDisplay() {}

    protected function _displayProduct(Product $product)
    {
        echo '<div class="productListView_product">';
            echo '<h2>'.$product->getName().'</h2>';
            echo '<img src="'.$product->getImage().'" style="width: 100%;" />';

            echo '<div>';
                echo '&pound; '.number_format($product->getPriceInPence() / 100, 2);
            echo '</div>';

            echo '<div>';
                echo $product->getShortDescription();
            echo '</div>';

            echo '<div>';
                echo implode(' ', $this->_getActionsForProduct($product));
            echo '</div>';
        echo '</div>';
    }

    protected function _getActionsForProduct(Product $product)
    {
        $viewURI = $this->_router->getURI($this->_viewProductRouteTemplate->getRouteWithReplacements(array('#id#' => $product->getId())));

        $ret = array();
        if ($viewURI != '')
            $ret[] = '<form method="GET" class="actionForm" action="'.$viewURI.'"><input type="submit" class="actionButton" value="View" /></form>';

        return $ret;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $viewProductRouteTemplate
     */
    public function setViewProductRouteTemplate($viewProductRouteTemplate)
    {
        $this->_viewProductRouteTemplate = $viewProductRouteTemplate;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getViewProductRouteTemplate()
    {
        return $this->_viewProductRouteTemplate;
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
}