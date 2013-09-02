<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Shorty\Helpers\ViewHelper\ViewHelper;

interface ViewHelperConsumer
{
    public function consumeViewHelper(ViewHelper $viewHelper);
}