<?php

namespace CannyDain\ShortyModules\Comments\Views;

use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\Views\ShortyView;
use CannyDain\ShortyModules\Comments\Models\Comment;

class CommentsListView extends ShortyView
{
    /**
     * @var Comment[]
     */
    protected $_comments;

    /**
     * @var ViewInterface
     */
    protected $_addCommentForm = null;

    public function display()
    {
        echo '<div class="comments">';
            echo '<h2>Comments</h2>';
            echo '<div class="commentCount">';
                echo count($this->_comments).' comment(s)';
            echo '</div>';

            foreach ($this->_comments as $comment)
                $this->_displayComment($comment);

            $this->_displayAddCommentForm();
        echo '</div>';
    }

    protected function _displayComment(Comment $comment)
    {
        echo '<div class="comment">';
            echo '<div class="meta">';
                echo '<div class="author">';
                    echo $comment->getAuthor();
                echo '</div>';

                echo '<div class="postDate">';
                    echo date('Y-m-d H:i', $comment->getPostedAt());
                echo '</div>';
            echo '</div>';

            echo '<div class="post">';
                echo '<div class="subject">';
                    echo $comment->getSubject();
                echo '</div>';

                echo '<div class="content">';
                    echo $comment->getComment();
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }

    protected function _displayAddCommentForm()
    {
        if ($this->_addCommentForm == null)
            return;

        $this->_addCommentForm->display();
    }

    /**
     * @param \CannyDain\Lib\UI\Views\ViewInterface $addCommentForm
     */
    public function setAddCommentForm($addCommentForm)
    {
        $this->_addCommentForm = $addCommentForm;
    }

    /**
     * @return \CannyDain\Lib\UI\Views\ViewInterface
     */
    public function getAddCommentForm()
    {
        return $this->_addCommentForm;
    }

    public function setComments($comments)
    {
        $this->_comments = $comments;
    }

    public function getComments()
    {
        return $this->_comments;
    }
}