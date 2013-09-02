<?php

namespace CannyDain\Lib\UI\Views;

use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\RequestConsumer;

class RedirectView implements ViewInterface, RequestConsumer
{
    const RESPONSE_CODE_TEMPORARY_REDIRECT = 302;
    const RESPONSE_CODE_PERMANENT_REDIRECT = 301;

    public static $passThroughParameters = array();

    /**
     * @var Request
     */
    protected $_request;
    protected $_uri = '';
    protected $_responseCode = self::RESPONSE_CODE_TEMPORARY_REDIRECT;

    public function __construct($uri = '', $responseCode = self::RESPONSE_CODE_TEMPORARY_REDIRECT)
    {
        $this->_uri = $uri;
        $this->_responseCode = $responseCode;
    }

    protected function _getRedirectURI()
    {
        $params = array();
        foreach (self::$passThroughParameters as $param)
        {
            $value = $this->_request->getParameter($param);
            if ($value != null)
                $params[] = $param.'='.urlencode($value);
        }

        $uri = $this->_uri;

        if (count($params) == 0)
            return $uri;

        if (strpos($uri, '?') === false)
            $uri .= '?';
        else
            $uri .= '&';

        $uri .= implode('&', $params);
        return $uri;
    }

    public function display()
    {
        header("Location: ".$this->_getRedirectURI(), true, $this->_responseCode);
    }

    public function setResponseCode($responseCode)
    {
        $this->_responseCode = $responseCode;
    }

    public function getResponseCode()
    {
        return $this->_responseCode;
    }

    public function setUri($uri)
    {
        $this->_uri = $uri;
    }

    public function getUri()
    {
        return $this->_uri;
    }

    public function getContentType()
    {
        return self::CONTENT_TYPE_NONE;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }
}