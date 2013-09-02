<?php

namespace CannyDain\Shorty\Bootstrap;

class DevBootstrap extends BaseBootstrap
{
    protected function _dependencyFactory()
    {
        return new DevDependencyFactory();
    }

}