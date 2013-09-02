<?php

namespace CannyDain\ShortyModules\SimpleShop\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Views\ShortyView;
use CannyDain\ShortyModules\SimpleShop\Models\Product;

class SingleProductView extends ShortyView
{
    /**
     * @var Product
     */
    protected $_product;

    /**
     * @var Route
     */
    protected $_addToBasketRoute;

    public function display()
    {
        echo '<h1>'.$this->_product->getName().'</h1>';

        echo '<div style="display: inline-block; vertical-align: top; width: 19%; margin-right: 1%;">';
            echo '<img src="'.$this->_product->getImage().'" style="width: 100%;" />';
            echo '<div class="price">';
                echo '&pound;'.number_format($this->_product->getPriceInPence() / 100, 2);
            echo '</div>';

            echo '<form class="actionForm" method="POST" action="'.$this->_router->getURI($this->_addToBasketRoute).'">';
                echo '<input type="submit" class="actionButton" value="Add to Basket" />';
            echo '</form>';
        echo '</div>';

        echo '<div style="display: inline-block; vertical-align: top; width: 80%; ">';
            echo $this->_product->getLongDescription();
        echo '</div>';
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $addToBasketRoute
     */
    public function setAddToBasketRoute($addToBasketRoute)
    {
        $this->_addToBasketRoute = $addToBasketRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getAddToBasketRoute()
    {
        return $this->_addToBasketRoute;
    }

    /**
     * @param \CannyDain\ShortyModules\SimpleShop\Models\Product $product
     */
    public function setProduct($product)
    {
        $this->_product = $product;
    }

    /**
     * @return \CannyDain\ShortyModules\SimpleShop\Models\Product
     */
    public function getProduct()
    {
        return $this->_product;
    }
}