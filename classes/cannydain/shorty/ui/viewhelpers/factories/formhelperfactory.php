<?php

namespace CannyDain\Shorty\UI\ViewHelpers\Factories;

use CannyDain\Lib\DependencyInjection\Interfaces\DependencyFactoryInterface;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;

class FormHelperFactory implements DependencyFactoryInterface
{
    public function createInstance($consumerInterface)
    {
        return new FormHelper();
    }
}