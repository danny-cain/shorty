<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Lib\UI\ResponsiveLayout\ResponsiveLayoutFactory;

interface ResponsiveLayoutConsumer extends ConsumerInterface
{
    public function consumeResponsiveLayoutFactory(ResponsiveLayoutFactory $dependency);
}