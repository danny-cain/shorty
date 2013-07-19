<?php

namespace CannyDain\Lib\Web\Client\Factories;

use CannyDain\Lib\Web\Client\Response\RawResponse;
use CannyDain\Lib\Web\Client\Response\ResponseInterface;

class ResponseFactory
{
    /**
     * @param $info
     * @param $headers
     * @param $responseBody
     * @return \CannyDain\Lib\Web\Client\Response\RawResponse
     */
    public function parseResponse($info, $headers, $responseBody)
    {
        $response = new RawResponse();

        $response->setBody($responseBody);
        $response->setContentType($info['content_type']);

        return $response;
    }
}