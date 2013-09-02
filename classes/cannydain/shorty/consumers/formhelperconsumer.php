<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Shorty\Helpers\Forms\FormHelperInterface;

interface FormHelperConsumer
{
    public function consumeFormHelper(FormHelperInterface $helper);
}