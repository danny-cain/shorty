<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Lib\Emailing\EmailerInterface;

interface EmailerConsumer extends ConsumerInterface
{
    public function consumeEmailer(EmailerInterface $dependency);
}