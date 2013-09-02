<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\GUIDS\GUIDManagerInterface;

interface GUIDConsumer
{
    public function consumeGUIDManager(GUIDManagerInterface $guidManager);
}