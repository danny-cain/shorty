<?php

namespace CannyDain\ShortyModules\Invoice\Views;

use CannyDain\Shorty\Views\ShortyView;

class PayByInvoiceView extends ShortyView
{
    public function display()
    {
        echo '<h1>Invoice Created</h1>';
        echo '<p>Your invoice has been created and should be on it\'s way to you shortly.</p>';
    }
}