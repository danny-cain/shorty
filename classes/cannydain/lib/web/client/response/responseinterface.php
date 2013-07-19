<?php

namespace CannyDain\Lib\Web\Client\Response;

interface ResponseInterface
{
    public function getContentType();
    public function getRawBody();
}