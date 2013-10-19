<?php

namespace CannyDain\Shorty\Routing\Models;

use CannyDain\Lib\Routing\Models\Route;

class RouteInfo extends Route
{
    protected $_name = '';
    protected $_type = '';

    public function __construct($name = '', $type = '', $controller = '', $method = '', $params = array(), $requestParams = array())
    {
        $this->_name = $name;
        $this->_type = $type;

        parent::__construct($controller, $method, $params, $requestParams); // TODO: Change the autogenerated stub
    }


    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
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