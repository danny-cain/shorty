<?php

namespace CannyDain\Shorty\Navigation;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;

abstract class BaseNavigationProvider implements NavigationProvider, RouterConsumer, RequestConsumer
{
    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var Request
     */
    protected $_request;

    protected function _isCurrentPage($uri)
    {
        $currentRoute = $this->_router->getRoute($this->_request->getResource());
        $testRoute = $this->_router->getRoute($uri);

        return $currentRoute->isEqualTo($testRoute);
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }


    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }
}