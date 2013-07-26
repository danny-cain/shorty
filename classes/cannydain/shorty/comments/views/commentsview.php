<?php

namespace CannyDain\Shorty\Comments\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Lib\Utils\Date\DateFormatManager;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Comments\Models\Comment;
use CannyDain\Shorty\Consumers\DateTimeConsumer;

class CommentsView extends HTMLView implements DateTimeConsumer
{
    /**
     * @var Comment[]
     */
    protected $_comments;

    protected $_guid;

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
    protected $_saveSettingsURI = '';

    protected $_showComments = false;
    protected $_canAddComments = false;

    public function setGuid($guid)
    {
        $this->_guid = $guid;
    }

    public function getGuid()
    {
        return $this->_guid;
    }

    public function setCanAddComments($canAddComments)
    {
        $this->_canAddComments = $canAddComments;
    }

    public function getCanAddComments()
    {
        return $this->_canAddComments;
    }

    public function setShowComments($showComments)
    {
        $this->_showComments = $showComments;
    }

    public function getShowComments()
    {
        return $this->_showComments;
    }

    public function setSaveSettingsURI($saveSettingsURI)
    {
        $this->_saveSettingsURI = $saveSettingsURI;
    }

    public function getSaveSettingsURI()
    {
        return $this->_saveSettingsURI;
    }

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

    public function updateFromRequest(Request $request)
    {
        $this->_showComments = $request->getParameter('showComments') == '1';
        $this->_canAddComments = $request->getParameter('addComments') == '1';
        $this->_guid = $request->getParameter('guid');
        $this->_deleteCommentReturnToURI = $request->getParameter('returnTo');
    }

    protected function _displaySettings()
    {
        $showChecked = '';
        $addChecked = '';

        if ($this->_canAddComments)
            $addChecked = ' checked="checked"';
        if ($this->_showComments)
            $showChecked = ' checked="checked"';

        echo '<form method="post" action="'.$this->_saveSettingsURI.'">';
            echo '<input type="hidden" name="returnTo" value="'.$this->_deleteCommentReturnToURI.'" />';
            echo '<input type="hidden" name="guid" value="'.$this->_guid.'" />';
            echo '<div>';
                echo '<input type="checkbox" name="showComments" value="1"'.$showChecked.'/> ';
                echo 'Show Comments';
            echo '</div>';

            echo '<div>';
                echo '<input type="checkbox" name="addComments" value="1"'.$addChecked.'/> ';
                echo 'Can Add Comments';
            echo '</div>';

            echo '<input type="submit" value="Save" />';
        echo '</form>';
    }

    public function display()
    {
        if ($this->_saveSettingsURI != '')
            $this->_displaySettings();

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