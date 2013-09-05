<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Shorty\Finance\InvoiceManager;

interface InvoiceManagerConsumer
{
    public function consumeInvoiceManager(InvoiceManager $manager);
}