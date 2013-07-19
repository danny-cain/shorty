<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Shorty\Routing\URIManager;

interface URIManagerConsumer extends ConsumerInterface
{
    public function consumeURIManager(URIManager $dependency);
}