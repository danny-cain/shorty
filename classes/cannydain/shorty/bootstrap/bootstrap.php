<?php

namespace CannyDain\Shorty\Bootstrap;

use CannyDain\Shorty\Config\ShortyConfiguration;
use CannyDain\Shorty\Execution\AppMain;

interface Bootstrap
{
    public function executeBootstrap(ShortyConfiguration $config, AppMain $main);
}