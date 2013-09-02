<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\Emailing\EmailerInterface;

interface EmailConsumer
{
    public function consumeEmailer(EmailerInterface $emailer);
}