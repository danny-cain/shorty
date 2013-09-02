<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Shorty\Modules\ModuleManager;

interface ModuleConsumer
{
    public function consumeModuleManager(ModuleManager $manager);
}