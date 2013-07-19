<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Lib\Utils\Date\DateFormatManager;

interface DateTimeConsumer extends ConsumerInterface
{
    public function consumeDateTimeManager(DateFormatManager $dependency);
}