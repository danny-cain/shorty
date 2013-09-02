<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\CommentsManager\CommentsManager;

interface CommentsConsumer
{
    public function consumeCommentsManager(CommentsManager $manager);
}