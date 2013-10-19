<?php

namespace CannyDain\Shorty\FileManager\Models;

use CannyDain\Shorty\Routing\Models\RouteInfo;

class PageTypeModel
{
    protected $_type = '';
    protected $_name = '';
    /**
     * @var RouteInfo[]
     */
    protected $_routes = array();

    function __construct($_name = '', $_type = '', $_routes = array())
    {
        $this->_name = $_name;
        $this->_routes = $_routes;
        $this->_type = $_type;
    }


    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setRoutes($routes)
    {
        $this->_routes = $routes;
    }

    public function getRoutes()
    {
        return $this->_routes;
    }

    public function setType($type)
    {
        $this->_type = $type;
    }

    public function getType()
    {
        return $this->_type;
    }
}