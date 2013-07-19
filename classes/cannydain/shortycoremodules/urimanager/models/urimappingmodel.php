<?php

namespace CannyDain\ShortyCoreModules\URIManager\Models;

class URIMappingModel
{
    const MODEL_CLASS_NAME = __CLASS__;

    protected $_id = 0;
    protected $_uri = '';
    protected $_controller = '';
    protected $_method = '';
    protected $_params = array();

    public function setController($controller)
    {
        $this->_controller = $controller;
    }

    public function getController()
    {
        return $this->_controller;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setMethod($method)
    {
        $this->_method = $method;
    }

    public function getMethod()
    {
        return $this->_method;
    }

    public function setParams($params)
    {
        $this->_params = $params;
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function setUri($uri)
    {
        $this->_uri = $uri;
    }

    public function getUri()
    {
        return $this->_uri;
    }
}