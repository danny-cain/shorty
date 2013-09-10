<?php

namespace CannyDain\Shorty\ECommerce\Products;

class ProductManager
{
    /**
     * @var ProductProviderInterface[]
     */
    protected $_providers = array();

    public function registerProvider(ProductProviderInterface $provider)
    {
        $this->_providers[] = $provider;
    }

    public function getProductInfo($guid)
    {
        foreach ($this->_providers as $provider)
        {
            $prod = $provider->getProductInfo($guid);
            if ($prod != null)
                return $prod;
        }

        return null;
    }
}