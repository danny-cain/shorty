<?php

namespace CannyDain\Shorty\Views;

use CannyDain\Lib\Exceptions\CannyLibException;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;

class NotAuthorisedView extends ExceptionView
{
    public function display()
    {
        echo '<h1>Access Denied</h1>';
        echo '<p>You do not have permission to access this page.</p>';
    }
}