<?php

namespace CannyDain\ShortyModules\Comments\Events;

use CannyDain\ShortyModules\Comments\Models\Comment;

interface CommentPostedEvent
{
    public function _event_commentPosted(Comment $comment);
}