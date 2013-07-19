<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Lib\Web\Server\Request;

interface RequestConsumer extends ConsumerInterface
{
    public function consumeRequest(Request $dependency);
}