<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Lib\UI\ViewFactory;

interface ViewFactoryConsumer extends ConsumerInterface
{
    public function consumeViewFactory(ViewFactory $dependency);
}