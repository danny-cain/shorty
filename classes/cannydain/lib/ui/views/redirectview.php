<?php

namespace CannyDain\Lib\UI\Views;

class RedirectView implements ViewInterface
{
    const RESPONSE_CODE_TEMPORARY_REDIRECT = 302;
    const RESPONSE_CODE_PERMANENT_REDIRECT = 301;

    protected $_uri = '';
    protected $_responseCode = self::RESPONSE_CODE_TEMPORARY_REDIRECT;

    public function __construct($uri = '', $responseCode = self::RESPONSE_CODE_TEMPORARY_REDIRECT)
    {
        $this->_uri = $uri;
        $this->_responseCode = $responseCode;
    }

    public function display()
    {
        header("Location: ".$this->_uri, true, $this->_responseCode);
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
}