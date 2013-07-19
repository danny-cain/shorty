<?php

namespace CannyDain\Sites\DannyCain\Controllers;

use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Sites\DannyCain\Views\HomepageView;

class HomepageController extends ShortyController
{
    public function Index()
    {
        return new HomepageView();
    }
}