<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\Web\Server\Request;

interface RequestConsumer
{
    public function consumeRequest(Request $request);
}