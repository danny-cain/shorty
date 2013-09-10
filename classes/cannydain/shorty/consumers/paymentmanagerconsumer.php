<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Shorty\Finance\PaymentManager;

interface PaymentManagerConsumer
{
    public function consumePaymentManager(PaymentManager $manager);
}