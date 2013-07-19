<?php

namespace CannyDain\Lib\Web\Client\RequestAutomation;

use CannyDain\Lib\Web\Client\HTTPClient;
use CannyDain\Lib\Web\Client\Models\Request;
use CannyDain\Lib\Web\Client\RequestAutomation\Listeners\ResponseListener;

class AutomatedRequests
{
    /**
     * @var Request[]
     */
    protected $_requests = array();

    /**
     * @var ResponseListener[]
     */
    protected $_responseListeners = array();

    /**
     * @var HTTPClient
     */
    protected $_httpClient;

    /**
     * @param \CannyDain\Lib\Web\Client\HTTPClient $httpClient
     */
    public function setHttpClient($httpClient)
    {
        $this->_httpClient = $httpClient;
    }

    public function addListener($listener)
    {
        if ($listener instanceof ResponseListener)
            $this->_responseListeners[] = $listener;
    }

    public function addRequest(Request $request)
    {
        $this->_requests[] = $request;
    }

    public function execute()
    {
        if ($this->_httpClient == null)
            $this->_httpClient = new HTTPClient();

        foreach ($this->_requests as $request)
            $this->_executeRequest($request);
    }

    protected function _executeRequest(Request $request)
    {
        $requestStart = microtime(true);
        $response = $this->_httpClient->requestPage($request);
        $requestFinish = microtime(true);

        $requestTime = $requestStart - $requestFinish;
        foreach ($this->_responseListeners as $listener)
            $listener->responseReceived($request, $response, $requestTime);
    }
}