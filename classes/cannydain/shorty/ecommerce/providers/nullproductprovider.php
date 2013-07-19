<?php

namespace CannyDain\Shorty\ECommerce\Providers;

use CannyDain\Shorty\ECommerce\Models\ShortyProductModel;

class NullProductProvider implements ProductProvider
{
    /**
     * @param $guid
     * @return ShortyProductModel
     */
    public function getProductByGUID($guid)
    {
        return null;
    }

    public function reduceStock($productGUID, $amount, $reason)
    {

    }

    public function increaseStock($productGUID, $amount, $reason)
    {

    }
}