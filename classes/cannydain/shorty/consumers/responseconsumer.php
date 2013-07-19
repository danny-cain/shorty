<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Lib\UI\Response\Response;

interface ResponseConsumer extends ConsumerInterface
{
    public function consumeResponse(Response $dependency);
}