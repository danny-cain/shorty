<?php

namespace CannyDain\Shorty\Routing;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Routing\Models\ModuleMap;

class MappedModuleRouter implements RouterInterface
{
    /**
     * @var ModuleMap[]
     */
    protected $_mappings = array();

    public function __construct($mappings = array())
    {
        $this->_mappings = $mappings;
    }


    public function addMapping(ModuleMap $map) { $this->_mappings[] = $map; }

    public function getURI(Route $route)
    {
        $map = $this->_getMappingByController($route->getController());
        if ($map == null)
            return null;

        $controllerName = substr($route->getController(),strlen($map->getControllerNamespace()));

        $uri = '/'.$map->getAlias().'/'.strtr($controllerName,array('\\' => '-')).'/'.$route->getMethod();
        foreach ($route->getParams() as $param)
            $uri .= '/'.$param;

        if (count($route->getRequestParameters()) > 0)
            $uri .= '?'.$route->getRequestParametersAsURIEncodedString();

        return $uri;
    }

    /**
     * @param $controller
     * @return ModuleMap|null
     */
    protected function _getMappingByController($controller)
    {
        foreach($this->_mappings as $mapping)
        {
            $namespace = $mapping->getControllerNamespace();
            $controllerNamespace = substr($controller, 0 ,strlen($namespace));

            if (strtolower($namespace) == strtolower($controllerNamespace))
                return $mapping;
        }

        return null;
    }

    protected function _getMappingByAlias($alias)
    {
        foreach ($this->_mappings as $map)
        {
            if (strtolower($map->getAlias()) == strtolower($alias))
                return $map;
        }
        return null;
    }

    /**
     * @param $uri
     * @return Route
     */
    public function getRoute($uri)
    {
        $parts = explode('/', $uri);
        $module = array_shift($parts);
        if ($module == '')
            $module = array_shift($parts);

        $controller = array_shift($parts);
        $method = array_shift($parts);

        $params = $parts;

        $map = $this->_getMappingByAlias($module);

        if ($map == null)
            return null;

        $controllerName = $map->getControllerNamespace().strtr($controller,array('-' => '\\'));
        if ($method == '')
            return new Route($controllerName);

        if(!is_array($parts))
            $parts = array();

        return new Route($controllerName, $method, $parts);
    }
}