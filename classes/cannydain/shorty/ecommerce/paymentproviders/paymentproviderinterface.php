<?php

namespace CannyDain\Shorty\ECommerce\PaymentProviders;

use CannyDain\Lib\UI\Views\ViewInterface;

interface PaymentProviderInterface
{
    /**
     * @return ViewInterface
     */
    public function getCheckoutButton();
}