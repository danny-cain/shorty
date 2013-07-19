<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\CommentsManager\CommentsManager;
use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;

interface CommentsConsumer extends ConsumerInterface
{
    public function consumeCommentsManager(CommentsManager $manager);
}