<?php

namespace CannyDain\ShortyCoreModules\Contact\Views;

use CannyDain\Lib\UI\Views\HTMLView;

class ThankYouView extends HTMLView
{
    public function display()
    {
        echo '<h1>Thank you</h1>';

        echo '<p>';
            echo 'Thank you for your message. It will be read and acted upon if necessary as soon as possible.';
        echo '</p>';
    }
}