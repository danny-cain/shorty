<?php

namespace CannyDain\Shorty\Events\Events;

use CannyDain\Lib\DataMapping\DataMapperInterface;

interface RegisterDataEvent
{
    public function _event_registerData(DataMapperInterface $datamapper);
}