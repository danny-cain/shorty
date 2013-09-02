<?php

namespace CannyDain\ShortyModules\SimpleShop\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Helpers\Forms\FormHelperInterface;
use CannyDain\Shorty\Helpers\Forms\Models\RichtextField;
use CannyDain\Shorty\Helpers\Forms\Models\SubmitButton;
use CannyDain\Shorty\Helpers\Forms\Models\TextboxField;
use CannyDain\Shorty\Views\ShortyFormView;
use CannyDain\ShortyModules\SimpleShop\Models\Product;

class EditProductView extends ShortyFormView
{
    const FIELD_NAME = 'name';
    const FIELD_SHORT_DESC = 'short-desc';
    const FIELD_LONG_DESC = 'long-desc';
    const FIELD_PRICE = 'price';
    const FIELD_STOCK = 'stock';
    const FIELD_IMAGE = 'image';

    /**
     * @var Route
     */
    protected $_postRoute;

    /**
     * @var Product
     */
    protected $_product;

    /**
     * @return bool
     */
    public function updateFromPostAndReturnTrueIfPostedAndValid()
    {
        if (!$this->_request->isPost())
            return false;

        $this->_setupForm();

        $this->_formHelper->updateFromRequest($this->_request);

        $this->_product->setName($this->_formHelper->getField(Product::FIELD_NAME)->getValue());
        $this->_product->setPriceInPence($this->_formHelper->getField(Product::FIELD_PRICE)->getValue());
        $this->_product->setStockLevel($this->_formHelper->getField(Product::FIELD_STOCK)->getValue());
        $this->_product->setImage($this->_formHelper->getField(Product::FIELD_IMAGE)->getValue());
        $this->_product->setShortDescription($this->_formHelper->getField(Product::FIELD_SHORT_DESC)->getValue());
        $this->_product->setLongDescription($this->_formHelper->getField(Product::FIELD_LONG_DESC)->getValue());

        $errors = $this->_product->validateAndReturnErrors();
        foreach ($errors as $field => $error)
            $this->_formHelper->getField($field)->setErrorText($error);

        return count($errors) == 0;
    }

    public function display()
    {
        $this->_setupForm();

        if ($this->_product->getId() > 0)
            $title = 'Editing '.$this->_product->getName();
        else
            $title = 'Add Product';

        echo '<h1>'.$title.'</h1>';

        $this->_formHelper->displayForm();
    }

    protected function _setupForm()
    {
        if ($this->_formHelper->getField(self::FIELD_NAME) != null)
            return;

        $this->_formHelper->setMethod(FormHelperInterface::FORM_METHOD_POST)
                          ->setURI($this->_router->getURI($this->_postRoute))
                          ->addField(new TextboxField('Name', Product::FIELD_NAME, $this->_product->getName(), 'The name of the product'))
                          ->addField(new TextboxField('Price', Product::FIELD_PRICE, $this->_product->getPriceInPence(), 'The price (in pence) of the product'))
                          ->addField(new TextboxField('Stock Level', Product::FIELD_STOCK, $this->_product->getStockLevel(), 'The current stock level'))
                          ->addField(new TextboxField('Image', Product::FIELD_IMAGE, $this->_product->getImage(), 'The path to the image (relative to the website root)'))
                          ->addField(new TextboxField('Short Description', Product::FIELD_SHORT_DESC, $this->_product->getShortDescription(), 'A short description of the product'))
                          ->addField(new RichtextField('Description', Product::FIELD_LONG_DESC, $this->_product->getLongDescription(), 'A full description of the product'))
                          ->addField(new SubmitButton('Save'));
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $postRoute
     */
    public function setPostRoute($postRoute)
    {
        $this->_postRoute = $postRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getPostRoute()
    {
        return $this->_postRoute;
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