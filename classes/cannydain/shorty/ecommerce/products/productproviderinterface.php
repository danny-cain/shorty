<?php

namespace CannyDain\Shorty\ECommerce\Products;

use CannyDain\Shorty\ECommerce\Products\Models\ProductInfoModel;

interface ProductProviderInterface
{
    /**
     * @param $guid
     * @return ProductInfoModel
     */
    public function getProductInfo($guid);
}