<?php

namespace CannyDain\Lib\Web\Client\RequestAutomation\Listeners;

use CannyDain\Lib\Web\Client\Models\Request;
use CannyDain\Lib\Web\Client\Response\ResponseInterface;

interface ResponseListener
{
    public function responseReceived(Request $request, ResponseInterface $response, $requestTime = 0);
}