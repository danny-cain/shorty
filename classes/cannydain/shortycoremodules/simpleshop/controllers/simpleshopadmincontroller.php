<?php

namespace CannyDain\ShortyCoreModules\SimpleShop\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\ShortyCoreModules\SimpleShop\DataAccess\SimpleShopDataAccess;
use CannyDain\ShortyCoreModules\SimpleShop\Models\SimpleShopProduct;
use CannyDain\ShortyCoreModules\SimpleShop\Views\EditProductView;
use CannyDain\ShortyCoreModules\SimpleShop\Views\ProductListView;

class SimpleShopAdminController implements ControllerInterface, RequestConsumer, DependencyConsumer, RouterConsumer
{
    const CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;
    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return true;
    }

    public function Index()
    {
        $view = $this->_view_ListProducts($this->datasource()->getAllProducts());

        return $view;
    }

    public function CreateProduct()
    {
        $product = new SimpleShopProduct();
        $view = $this->_view_EditProduct($product);

        if ($this->_request->isPost())
        {
            $view->updateFromRequest($this->_request);
            $this->datasource()->saveProduct($view->getProduct());
            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
        }
        return $view;
    }

    public function EditProduct($id)
    {
        $product = $this->datasource()->getProduct($id);
        $view = $this->_view_EditProduct($product);

        if ($this->_request->isPost())
        {
            $view->updateFromRequest($this->_request);
            $this->datasource()->saveProduct($view->getProduct());
            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
        }
        return $view;
    }

    protected function _view_EditProduct(SimpleShopProduct $product)
    {
        $view = new EditProductView();
        $this->_dependencies->applyDependencies($view);

        $view->setProduct($product);

        if ($product->getId() > 0)
            $view->setSaveURI($this->_router->getURI(new Route(__CLASS__, 'EditProduct', array($product->getId()))));
        else
            $view->setSaveURI($this->_router->getURI(new Route(__CLASS__, 'CreateProduct')));

        return $view;
    }

    protected function _view_ListProducts($products)
    {
        $view = new ProductListView();

        $this->_dependencies->applyDependencies($view);
        $view->setProducts($products);
        $view->setTitle('Shop Administration');
        $view->setViewProductLinkTemplate($this->_router->getURI(new Route(__CLASS__, 'EditProduct', array('#id#'))));
        $view->setCreateProductURI($this->_router->getURI(new Route(__CLASS__, 'CreateProduct')));

        return $view;
    }

    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new SimpleShopDataAccess();
            $this->_dependencies->applyDependencies($datasource);
            $this->datasource()->registerObjects();
        }

        return $datasource;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }
}