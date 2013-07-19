<?php

namespace CannyDain\Shorty\ECommerce\Providers;

use CannyDain\Shorty\ECommerce\Models\ShortyProductModel;

interface ProductProvider
{
    /**
     * @param $guid
     * @return ShortyProductModel
     */
    public function getProductByGUID($guid);

    public function reduceStock($productGUID, $amount, $reason);
    public function increaseStock($productGUID, $amount, $reason);
}