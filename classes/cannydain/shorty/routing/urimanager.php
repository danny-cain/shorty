<?php

namespace CannyDain\Shorty\Routing;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Routing\Widgets\AssignURIWidget;

class URIManager
{
    public function getAssignURIWidgetForRoute(Route $route, $fieldName = 'uri')
    {
        return new AssignURIWidget();
    }
}