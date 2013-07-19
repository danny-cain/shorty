<?php

namespace CannyDain\Shorty\Comments\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Lib\Utils\Date\DateFormatManager;
use CannyDain\Shorty\Comments\Models\Comment;
use CannyDain\Shorty\Consumers\DateTimeConsumer;

class CommentsView extends HTMLView implements DateTimeConsumer
{
    /**
     * @var Comment[]
     */
    protected $_comments;

    /**
     * @var DateFormatManager
     */
    protected $_dateManager;

    /**
     * @var ViewInterface
     */
    protected $_addCommentView;

    protected $_deleteCommentURITemplate = '';
    protected $_deleteCommentReturnToURI = '';

    /**
     * @param \CannyDain\Lib\UI\Views\ViewInterface $addCommentView
     */
    public function setAddCommentView($addCommentView)
    {
        $this->_addCommentView = $addCommentView;
    }

    public function setDeleteCommentReturnToURI($deleteCommentReturnToURI)
    {
        $this->_deleteCommentReturnToURI = $deleteCommentReturnToURI;
    }

    public function getDeleteCommentReturnToURI()
    {
        return $this->_deleteCommentReturnToURI;
    }

    /**
     * @return \CannyDain\Lib\UI\Views\ViewInterface
     */
    public function getAddCommentView()
    {
        return $this->_addCommentView;
    }

    public function setDeleteCommentURITemplate($deleteCommentURITemplate)
    {
        $this->_deleteCommentURITemplate = $deleteCommentURITemplate;
    }

    public function getDeleteCommentURITemplate()
    {
        return $this->_deleteCommentURITemplate;
    }

    public function setComments($comments)
    {
        $this->_comments = $comments;
    }

    public function getComments()
    {
        return $this->_comments;
    }

    public function display()
    {
        echo '<div class="comments">';
            foreach ($this->_comments as $comment)
                $this->_displayComment($comment);

            if ($this->_addCommentView != null)
                $this->_addCommentView->display();
        echo '</div>';
    }

    protected function _displayComment(Comment $comment)
    {
        echo '<div class="comment">';
            echo '<div class="commentMeta">';
                echo '<div class="author">'.$comment->getAuthorName().'</div>';
                echo '<div class="date">'.$this->_dateManager->getFormattedDateTime($comment->getPostedDateTime()).'</div>';
                echo '<div class="subject">'.$comment->getSubject().'</div>';
                if ($this->_deleteCommentURITemplate != '')
                {
                    $uri = strtr($this->_deleteCommentURITemplate, array('#id#' => $comment->getId()));
                    echo '<form method="post" action="'.$uri.'" class="delete" onsubmit="return confirm(\'Are you sure you wish to delete this comment?\');">';
                        echo '<input type="hidden" name="returnURI" value="'.$this->_deleteCommentReturnToURI.'" />';
                        echo '<input type="submit" value="Delete" />';
                    echo '</form>';
                }
            echo '</div>';

            echo '<div class="commentText">';
                echo $comment->getComment();
            echo '</div>';
        echo '</div>';
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDateTimeManager(DateFormatManager $dependency)
    {
        $this->_dateManager = $dependency;
    }
}