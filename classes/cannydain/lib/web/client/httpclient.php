<?php

namespace CannyDain\Lib\Web\Client;

use CannyDain\Lib\Web\Client\Factories\ResponseFactory;
use CannyDain\Lib\Web\Client\Models\Request;

class HTTPClient
{
    /**
     * @var ResponseFactory
     */
    protected $_responseFactory;
    protected $_cookieFile = '';

    public function setCookieFile($cookieFile)
    {
        $this->_cookieFile = $cookieFile;
    }

    public function requestPage(Request $request)
    {
        if ($this->_cookieFile == '')
            $this->_cookieFile = dirname(__FILE__).'/cookies.txt';

        if ($this->_responseFactory == null)
            $this->_responseFactory = new ResponseFactory();

        $curl = \curl_init($request->getUri());

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $this->_cookieFile);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->_cookieFile);

        if ($request->isPost())
        {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getParams());
        }

        $raw = curl_exec($curl);
        $info = curl_getinfo($curl);
        $bodyLength = $info['download_content_length'];

        if ($bodyLength > 0)
            $responseBody = substr($raw, -$bodyLength);
        else
            $responseBody = '';

        $allHeaders = explode("\r\n\r\n", substr($raw, 0, strlen($raw) - $bodyLength));
        $headers = array_pop($allHeaders);

        if (trim($headers) == '')
            $headers = array_pop($allHeaders);

        return $this->_responseFactory->parseResponse($info, $headers, $responseBody);
    }
}