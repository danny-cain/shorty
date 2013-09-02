<?php

namespace CannyDain\Shorty\Views\Errors;

use CannyDain\Lib\UI\Views\HTMLView;

class PageNotFoundView extends HTMLView
{
    public function display()
    {
        echo '<p>The page you requested could not be found.</p>';
    }
}