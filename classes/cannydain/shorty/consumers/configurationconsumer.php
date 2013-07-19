<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Shorty\Config\ShortyConfiguration;

interface ConfigurationConsumer extends ConsumerInterface
{
    public function consumeConfiguration(ShortyConfiguration $dependency);
}