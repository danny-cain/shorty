<?php

namespace CannyDain\Lib\Routing\Routers;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;

class DirectMappedRouter implements RouterInterface
{
    public function getURI(Route $route)
    {
        $controller = strtr($route->getController(), array('\\' => '-'));
        if (substr($controller, 0, 1) == '-')
            $controller = substr($controller, 1);

        $method = $route->getMethod();
        $params = $route->getParams();

        $uri = '/'.$controller.'/'.$method.'/'.implode('/', $params);
        while (strpos($uri, '//') !== false)
            $uri = str_replace('//', '/', $uri);

        if (substr($uri, strlen($uri) - 1) == '/' && strlen($uri) > 1)
            $uri = substr($uri, 0, strlen($uri) - 1);

        return $uri;
    }

    /**
     * @param $uri
     * @return Route
     */
    public function getRoute($uri)
    {
        $parts = explode('/', $uri);
        if (substr($uri, 0, 1) == '/')
            array_shift($parts);

        $route = new Route(strtr(array_shift($parts), array('-' => '\\')), array_shift($parts), $parts);

        return $route;
    }
}