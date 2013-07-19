<?php

namespace CannyDain\ShortyCoreModules\URIManager\Router;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\Routing\Routers\DirectMappedRouter;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\ShortyCoreModules\URIManager\DataAccess\URIManagerDataAccess;

class ManagedRouter extends DirectMappedRouter implements DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    public function getURI(Route $route)
    {
        $result = $this->datasource()->getBestMatchRoute($route);

        if ($result != null)
        {
            $ret = $result->getUri();

            if (substr($ret, strlen($ret) - 1) == '/')
                $ret = substr($ret, 0, strlen($ret) - 1);

            if ($result->getController() == '' && $route->getController() != '')
                $ret .= '/'.$route->getController();

            if ($result->getMethod() == '' && $route->getMethod() != '')
                $ret .= '/'.$route->getMethod();

            $params = $route->getParams();
            for ($i = count($result->getParams()); $i < count($route->getParams()); $i ++)
                $ret .= '/'.$params[$i];

            if (substr($ret, 0, 1) != '/')
                $ret = '/'.$ret;

            return $ret;
        }

        // fall through to parent if no match found
        return parent::getURI($route);
    }

    public function getRoute($uri)
    {
        $result = $this->datasource()->getBestMatchURI($uri);

        if ($result != null)
        {
            $route = new Route();
            $route->setController($result->getController());
            $route->setMethod($result->getMethod());
            $route->setParams($result->getParams());

            $leftOvers = substr($uri, strlen($result->getUri()));
            $parts = explode('/', $leftOvers);
            if (count($parts) > 0 && $parts[0] == '')
                array_shift($parts);

            if ($route->getController() == '')
                $route->setController(array_shift($parts));

            if ($route->getMethod() == '')
                $route->setMethod(array_shift($parts));

            $route->setParams(array_merge($route->getParams(), $parts));

            return $route;
        }

        // fall through to parent if no match found
        return parent::getRoute($uri);
    }

    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new URIManagerDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }
}