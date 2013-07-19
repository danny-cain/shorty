<?php

namespace CannyDain\Shorty\Comments\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Comments\Models\Comment;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\Consumers\UserControlConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\Shorty\UserControl\UserControl;

class AddCommentForm extends HTMLView implements FormHelperConsumer, UserControlConsumer
{
    /**
     * @var Comment
     */
    protected $_comment;
    protected $_returnURI = '';
    protected $_postURI = '';

    /**
     * @var UserControl
     */
    protected $_userControl;

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    public function setPostURI($postURI)
    {
        $this->_postURI = $postURI;
    }

    public function getPostURI()
    {
        return $this->_postURI;
    }

    /**
     * @param \CannyDain\Shorty\Comments\Models\Comment $comment
     */
    public function setComment($comment)
    {
        $this->_comment = $comment;
    }

    /**
     * @return \CannyDain\Shorty\Comments\Models\Comment
     */
    public function getComment()
    {
        return $this->_comment;
    }

    public function setReturnURI($returnURI)
    {
        $this->_returnURI = $returnURI;
    }

    public function getReturnURI()
    {
        return $this->_returnURI;
    }

    public function display()
    {
        if ($this->_userControl->getCurrentUserID() < 1)
        {
            echo '<p>You must be logged in to post a comment</p>';
            return;
        }

        echo '<h2>Add a comment</h2>';
        $this->_formHelper->startForm($this->_postURI);
            $this->_formHelper->hiddenField('uri', $this->_returnURI);
            $this->_formHelper->hiddenField('object', $this->_comment->getObjectGUID());
            $this->_formHelper->editText('name', 'Name', $this->_comment->getAuthorName(), 'Your name');
            $this->_formHelper->editText('email', 'Email', $this->_comment->getAuthorName(), 'Your email');
            $this->_formHelper->editText('subject', 'Subject', $this->_comment->getAuthorName(), 'The subject of your comment');
            $this->_formHelper->editLargeText('comment', 'Comment', $this->_comment->getAuthorName(), 'The bulk of your comment');
            $this->_formHelper->submitButton('Add Comment');
        $this->_formHelper->endForm();
    }

    public function updateFromPost(Request $request)
    {
        $this->_returnURI = $request->getParameter('uri');
        $this->_comment->setAuthorEmail($request->getParameter('email'));
        $this->_comment->setAuthorName($request->getParameter('name'));
        $this->_comment->setComment($request->getParameter('comment'));
        $this->_comment->setSubject($request->getParameter('subject'));
        $this->_comment->setObjectGUID($request->getParameter('object'));
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }

    public function consumeUserController(UserControl $dependency)
    {
        $this->_userControl = $dependency;
    }
}