<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Shorty\TimeTracking\TimeTracker;

interface TimeEntryConsumer extends ConsumerInterface
{
    public function consumeTimeTracker(TimeTracker $dependency);
}