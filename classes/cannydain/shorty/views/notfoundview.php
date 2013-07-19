<?php

namespace CannyDain\Shorty\Views;

use CannyDain\Lib\Exceptions\CannyLibException;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;

class NotFoundView extends ExceptionView
{
    public function display()
    {
        echo '<h1>The page you requested could not be found</h1>';
        echo '<p>We\'re not sure where it has gone, it may have popped out for a cup of tea.</p>';
    }
}