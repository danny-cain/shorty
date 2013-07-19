<?php

namespace CannyDain\Lib\Routing\Interfaces;

use CannyDain\Lib\Routing\Models\Route;

interface RouterInterface
{
    public function getURI(Route $route);

    /**
     * @param $uri
     * @return Route
     */
    public function getRoute($uri);
}