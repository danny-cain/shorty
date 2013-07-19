<?php

namespace CannyDain\Lib\Web\Server;

class Request
{
    protected $_parameters = array();
    protected $_cookies = array();
    protected $_requestedResource = '';
    protected $_requestMethod = '';

    public function getResource()
    {
        return $this->_requestedResource;
    }

    public function getParameters()
    {
        return $this->_parameters;
    }

    public function isPost()
    {
        return $this->_requestMethod == 'POST';
    }

    public function loadFromHTTPRequest($resourceParameter = 'r')
    {
        $this->_requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->_cookies = $_COOKIE;
        if (isset($_GET[$resourceParameter]))
        {
            $this->_requestedResource = $_GET[$resourceParameter];
            unset($_GET[$resourceParameter]);
        }

        switch($this->_requestMethod)
        {
            case 'POST':
                $this->_parameters = $_POST;
                break;
            default:
                $this->_parameters = $_GET;
                break;
        }
    }

    public function getCookie($cookie)
    {
        if (!isset($this->_cookies[$cookie]))
            return null;

        return $this->_cookies[$cookie];
    }

    public function getParameter($param)
    {
        if (!isset($this->_parameters[$param]))
            return null;

        return $this->_parameters[$param];
    }
}