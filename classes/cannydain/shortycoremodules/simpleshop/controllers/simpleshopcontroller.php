<?php

namespace CannyDain\ShortyCoreModules\SimpleShop\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\ECommerceConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Shorty\ECommerce\ECommerceManager;
use CannyDain\ShortyCoreModules\SimpleShop\DataAccess\SimpleShopDataAccess;
use CannyDain\ShortyCoreModules\SimpleShop\Views\AddDiscountForm;
use CannyDain\ShortyCoreModules\SimpleShop\Views\AddToBasketForm;
use CannyDain\ShortyCoreModules\SimpleShop\Views\BasketView;
use CannyDain\ShortyCoreModules\SimpleShop\Views\ProductListView;
use CannyDain\ShortyCoreModules\SimpleShop\Views\ProductView;

class SimpleShopController extends ShortyController implements ECommerceConsumer, DependencyConsumer, RequestConsumer, RouterConsumer
{
    const CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var ECommerceManager
     */
    protected $_ecommerce;

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return false;
    }

    public function ApplyDiscount()
    {
        if ($this->_request->isPost())
        {
            $view = new AddDiscountForm();
            $this->_dependencies->applyDependencies($view);

            $view->updateFromRequest($this->_request);
            $this->_ecommerce->getCurrentBasket()->setDiscountCode($view->getDiscountCode());
        }

        return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'Basket')));
    }

    public function Basket()
    {
        $this->_ecommerce->updateBasket();

        $discountForm = new AddDiscountForm();
        $this->_dependencies->applyDependencies($discountForm);
        $discountForm->setApplyURI($this->_router->getURI(new Route(__CLASS__, 'ApplyDiscount')));
        $discountForm->setDiscountCode($this->_ecommerce->getCurrentBasket()->getDiscountCode());

        $view = new BasketView();
        $this->_dependencies->applyDependencies($view);

        $view->setDiscountCodeForm($discountForm);
        $view->setBasket($this->_ecommerce->getCurrentBasket());
        $view->setPaymentProviders($this->_ecommerce->getPaymentProviders());

        return $view;
    }

    public function Index()
    {
        $view = new ProductListView();
        $this->_dependencies->applyDependencies($view);
        $view->setProducts($this->datasource()->getAllProducts());
        $view->setViewProductLinkTemplate($this->_router->getURI(new Route(__CLASS__, 'View', array('#id#'))));
        $view->setTitle('All Products');

        return $view;
    }

    public function View($productID)
    {
        $product = $this->datasource()->getProduct($productID);

        $addToBasketView = new AddToBasketForm();
        $view = new ProductView();

        $this->_dependencies->applyDependencies($addToBasketView);
        $this->_dependencies->applyDependencies($view);

        $addToBasketView->setAddToBasketURI($this->_router->getURI(new Route(__CLASS__, 'AddToBasket')));
        $addToBasketView->setProductID($product->getId());
        $addToBasketView->setQty(1);

        $view->setAddToBasketForm($addToBasketView);
        $view->setProduct($product);

        return $view;
    }

    public function AddToBasket()
    {
        if ($this->_request->isPost())
        {
            $addToBasketView = new AddToBasketForm();
            $this->_dependencies->applyDependencies($addToBasketView);
            $addToBasketView->updateModelFromRequest($this->_request);

            $product = $this->datasource()->getProduct($addToBasketView->getProductID());
            $this->_ecommerce->addToBasket($this->datasource()->getProductGUID($product->getId()), $addToBasketView->getQty());
        }

        if (!isset($product) || $product == null || $product->getId() == '')
            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));

        return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'View', array($product->getId()))));
    }

    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new SimpleShopDataAccess();
            $this->_dependencies->applyDependencies($datasource);
            $datasource->registerObjects();
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

    public function consumeECommerceManager(ECommerceManager $dependency)
    {
        $this->_ecommerce = $dependency;
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