<?php

namespace CannyDain\ShortyModules\Invoice\Views;

use CannyDain\Shorty\Views\ShortyView;

class PayByInvoiceView extends ShortyView
{
    public function display()
    {
        echo '<h1>Your invoice is on it\'s way</h1>';
        echo '<p>Your invoice should be with you withing 3 weeks.</p>';
    }
}