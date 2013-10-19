<?php

namespace CannyDain\Lib\Routing\Routers;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;

class FixedNamespaceRouter implements RouterInterface
{
    protected $_namespace = '';

    public function __construct($namespace)
    {
        if (substr($namespace, strlen($namespace) - 1) != '\\')
            $namespace = $namespace.'\\';

        $this->_namespace = $namespace;
    }

    public function getURI(Route $route)
    {
        $segments = explode('\\', $route->getController());
        $controllerName = array_pop($segments);
        if (strtolower(implode('\\', $segments).'\\') != strtolower($this->_namespace))
            return '';

        $controller = $controllerName;
        $method = $route->getMethod();
        $params = $route->getParams();

        $uri = '/'.$controller.'/'.$method.'/'.implode('/', $params);
        while (strpos($uri, '//') !== false)
            $uri = str_replace('//', '/', $uri);

        if (substr($uri, strlen($uri) - 1) == '/' && strlen($uri) > 1)
            $uri = substr($uri, 0, strlen($uri) - 1);

        if (count($route->getRequestParameters()) > 0)
            $uri .= '?'.$route->getRequestParametersAsURIEncodedString();

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

        $route = new Route($this->_namespace.array_shift($parts), array_shift($parts), $parts);

        return $route;
    }
}