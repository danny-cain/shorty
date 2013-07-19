<?php

namespace CannyDain\Lib\DependencyInjection\Interfaces;

use CannyDain\Lib\DependencyInjection\DependencyInjector;

interface DependencyFactoryInterface
{
    public function createInstance($consumerInterface);
}