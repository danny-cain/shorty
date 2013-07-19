<?php

namespace CannyDain\Lib\Web\Client\Models;

class Request
{
    const REQUEST_METHOD_POST = 'POST';
    const REQUEST_METHOD_GET = 'GET';

    protected $_uri = '';
    protected $_method = '';
    protected $_params = array();

    public function __construct($uri = '', $method = self::REQUEST_METHOD_GET, $params = array())
    {
        $this->_uri = $uri;
        $this->_method = $method;
        $this->_params = $params;
    }

    public function isPost()
    {
        return $this->_method == self::REQUEST_METHOD_POST;
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