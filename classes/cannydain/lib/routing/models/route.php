<?php

namespace CannyDain\Lib\Routing\Models;

class Route
{
    protected $_controller = '';
    protected $_method = '';
    protected $_params = array();
    protected $_requestParameters = array();

    public function __construct($controller = '', $method = '', $params = array(), $requestParams = array())
    {
        $this->_controller = $controller;
        $this->_method = $method;
        $this->_params = $params;
        $this->_requestParameters = $requestParams;
    }

    public function getRequestParametersAsURIEncodedString()
    {
        $ret = $this->flattenArrayForURIEncoding($this->_requestParameters);

        return implode('&', $ret);
    }

    protected function flattenArrayForURIEncoding($array, $prefix = '')
    {
        $ret = array();

        foreach ($array as $key => $val)
        {
            $key = urlencode($key);
            if ($prefix == '')
                $newKey = $key;
            else
                $newKey = $prefix.'['.$key.']';

            if (is_array($val))
            {
                $ret = array_merge($ret, $this->flattenArrayForURIEncoding($val, $newKey));
            }
            else
            {
                $val = urlencode($val);
                $ret[] = $newKey.'='.$val;
            }
        }

        return $ret;
    }

    public function setRequestParameters($requestParameters)
    {
        $this->_requestParameters = $requestParameters;
    }

    public function getRequestParameters()
    {
        return $this->_requestParameters;
    }

    public function getRouteWithReplacements($replacements = array())
    {
        $ret = clone($this);
        $ret->setController(strtr($ret->getController(), $replacements));
        $ret->setMethod(strtr($ret->getMethod(), $replacements));

        $params = array();
        foreach ($this->getParams() as $param)
            $params[] = strtr($param, $replacements);

        $ret->setRequestParameters($this->_doReplacementsOnArray($this->getRequestParameters(), $replacements));
        $ret->setParams($params);

        return $ret;
    }

    protected function _doReplacementsOnArray($array, $replacements)
    {
        $ret = array();

        foreach ($array as $key => $val)
        {
            if (is_array($val))
                $val = $this->_doReplacementsOnArray($val, $replacements);
            else
                $val = strtr($val, $replacements);

            $ret[$key] = $val;
        }

        return $ret;
    }

    /**
     * Checks if this route contains $rhs and returns an integer indicating how strong the match is (i.e. how many segments are set)
     * @param Route $rhs
     * @return int
     */
    public function getContainStrength(Route $rhs)
    {
        $rhsController = strtolower($rhs->getController());
        $controller = strtolower($this->getController());
        $rhsMethod = strtolower($rhs->getMethod());
        $method = strtolower($this->getMethod());
        $rhsParams = $rhs->getParams();
        $params = $this->getParams();

        if ($controller != $rhsController && $rhsController != '')
            return -1;

        if ($method != $rhsMethod && $rhsMethod != '')
            return -1;

        if (count($params) < count($rhsParams))
            return -1;

        foreach ($rhsParams as $rhsParam)
        {
            if (strtolower($rhsParam) != strtolower(array_shift($params)))
                return -1;
        }

        if ($rhsController == '')
            return 0;

        if ($rhsMethod == '')
            return 1;

        return count($rhsParams) + 1;
    }

    public function isEqualTo(Route $rhs)
    {
        if (strtolower($rhs->getController()) != strtolower($this->_controller))
            return false;

        if (strtolower($rhs->getMethod()) != strtolower($this->_method))
            return false;

        if (count($this->_params) != count($rhs->getParams()))
            return false;

        for ($i = 0; $i < count($this->_params); $i ++)
        {
            if (strtolower($this->_params[$i]) != strtolower($rhs->_params[$i]))
                return false;
        }

        return true;
    }

    public function setController($controller)
    {
        $this->_controller = $controller;
    }

    public function getController()
    {
        if (strlen($this->_controller) > 0 && substr($this->_controller, 0, 1) != '\\')
            $this->_controller = '\\'.$this->_controller;

        return $this->_controller;
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
}