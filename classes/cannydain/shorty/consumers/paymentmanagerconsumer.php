<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Shorty\Finances\PaymentManager;

interface PaymentManagerConsumer
{
    public function consumePaymentManager(PaymentManager $manager);
}