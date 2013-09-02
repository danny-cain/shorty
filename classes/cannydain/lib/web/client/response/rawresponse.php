<?php

namespace CannyDain\Lib\Web\Client\Response;

class RawResponse implements ResponseInterface
{
    protected $_contentType = '';
    protected $_body = '';

    public function getRawBody()
    {
        return $this->_body;
    }

    public function setBody($body)
    {
        $this->_body = $body;
    }

    public function getBody()
    {
        return $this->_body;
    }

    public function setContentType($contentType)
    {
        $this->_contentType = $contentType;
    }

    public function getContentType()
    {
        return $this->_contentType;
    }
}