<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Shorty\Modules\ModuleManager;

interface ModuleConsumer extends ConsumerInterface
{
    public function consumeModuleManager(ModuleManager $dependency);
}