<?php

namespace CannyDain\ShortyModules\SimpleShop\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\ShortyModules\SimpleShop\Models\Product;

class ProductAdminListView extends ProductListView
{
    /**
     * @var Route
     */
    protected $_editProductRouteTemplate;

    /**
     * @var Route
     */
    protected $_createProductRoute;

    protected function _preProductDisplay()
    {
        parent::_preProductDisplay(); // TODO: Change the autogenerated stub
        $this->_displayPageActions();
    }

    protected function _postProductDisplay()
    {
        parent::_postProductDisplay(); // TODO: Change the autogenerated stub
        $this->_displayPageActions();
    }

    protected function _displayPageActions()
    {
        echo '<div class="pageActions">';
            echo '<form class="actionForm" method="GET" action="'.$this->_router->getURI($this->_createProductRoute).'">';
                echo '<input type="submit" class="actionButton" value="Create" />';
            echo '</form>';
        echo '</div>';
    }

    protected function _getActionsForProduct(Product $product)
    {
        $editURI = $this->_router->getURI($this->_editProductRouteTemplate->getRouteWithReplacements(array('#id#' => $product->getId())));

        $actions = parent::_getActionsForProduct($product);
        if ($editURI != '')
            $actions[] = '<form method="GET" action="'.$editURI.'" class="actionForm"><input type="submit" value="Edit" /></form>';

        return $actions;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $createProductRoute
     */
    public function setCreateProductRoute($createProductRoute)
    {
        $this->_createProductRoute = $createProductRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getCreateProductRoute()
    {
        return $this->_createProductRoute;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $editProductRouteTemplate
     */
    public function setEditProductRouteTemplate($editProductRouteTemplate)
    {
        $this->_editProductRouteTemplate = $editProductRouteTemplate;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getEditProductRouteTemplate()
    {
        return $this->_editProductRouteTemplate;
    }
}