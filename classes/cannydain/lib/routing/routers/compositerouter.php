<?php

namespace CannyDain\Lib\Routing\Routers;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;

class CompositeRouter implements RouterInterface
{
    /**
     * @var RouterInterface[]
     */
    protected $_routers = array();

    /**
     * @param RouterInterface[] $routers
     */
    public function __construct($routers = array())
    {
        $this->_routers = $routers;
    }

    public function addRouter(RouterInterface $router)
    {
        $this->_routers[] = $router;
    }

    public function getURI(Route $route)
    {
        foreach ($this->_routers as $router)
        {
            $uri = $router->getURI($route);
            if ($uri != null)
                return $uri;
        }

        return null;
    }

    /**
     * @param $uri
     * @return Route
     */
    public function getRoute($uri)
    {
        foreach ($this->_routers as $router)
        {
            $route = $router->getRoute($uri);
            if ($route != null)
                return $route;
        }

        return null;
    }
}