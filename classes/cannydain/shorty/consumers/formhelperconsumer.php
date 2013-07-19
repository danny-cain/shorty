<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;

interface FormHelperConsumer extends ConsumerInterface
{
    public function consumeFormHelper(FormHelper $dependency);
}