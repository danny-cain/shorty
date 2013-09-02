<?php

namespace CannyDain\ShortyModules\Comments\Views;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Helpers\Forms\Models\HiddenField;
use CannyDain\Shorty\Helpers\Forms\Models\SubmitButton;
use CannyDain\Shorty\Helpers\Forms\Models\TextboxField;
use CannyDain\Shorty\Views\ShortyFormView;
use CannyDain\ShortyModules\Comments\Models\Comment;

class AddCommentForm extends ShortyFormView
{
    const FIELD_RETURN_URL = 'return-url';

    /**
     * @var Comment
     */
    protected $_comment;
    protected $_returnURL = '';
    /**
     * @var Route
     */
    protected $_postRoute;

    /**
     * @return bool
     */
    public function updateFromPostAndReturnTrueIfPostedAndValid()
    {
        if (!$this->_request->isPost())
            return false;

        $this->_setupForm();
        $this->_formHelper->updateFromRequest($this->_request);
        $this->_comment->setSubject($this->_formHelper->getField(Comment::FIELD_SUBJECT)->getValue());
        $this->_comment->setGuid($this->_formHelper->getField(Comment::FIELD_GUID)->getValue());
        $this->_comment->setComment($this->_formHelper->getField(Comment::FIELD_COMMENT)->getValue());
        $this->_returnURL = $this->_formHelper->getField(self::FIELD_RETURN_URL)->getValue();

        $errors = $this->_comment->validateAndReturnErrors();
        foreach ($errors as $field => $message)
            $this->_formHelper->getField($field)->setErrorText($message);

        return count($errors) == 0;
    }

    public function display()
    {
        $this->_setupForm();

        echo '<h2>Add Comment</h2>';
        $this->_formHelper->displayForm();
    }

    protected function _setupForm()
    {
        if ($this->_formHelper->getField(self::FIELD_RETURN_URL) != null)
            return;

        $this->_formHelper->setMethod('POST')
                          ->setURI($this->_router->getURI($this->_postRoute))
                          ->addField(new HiddenField(self::FIELD_RETURN_URL, $this->_returnURL))
                          ->addField(new HiddenField(Comment::FIELD_GUID, $this->_comment->getGuid()))
                          ->addField(new TextboxField('Subject', Comment::FIELD_SUBJECT, $this->_comment->getSubject(), 'The subject of your comment'))
                          ->addField(new TextboxField('Comment', Comment::FIELD_COMMENT, $this->_comment->getComment(), 'Your comments'))
                          ->addField(new SubmitButton('Post'));
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $postRoute
     */
    public function setPostRoute($postRoute)
    {
        $this->_postRoute = $postRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getPostRoute()
    {
        return $this->_postRoute;
    }

    /**
     * @param \CannyDain\ShortyModules\Comments\Models\Comment $comment
     */
    public function setComment($comment)
    {
        $this->_comment = $comment;
    }

    /**
     * @return \CannyDain\ShortyModules\Comments\Models\Comment
     */
    public function getComment()
    {
        return $this->_comment;
    }

    public function setReturnURL($returnURL)
    {
        $this->_returnURL = $returnURL;
    }

    public function getReturnURL()
    {
        return $this->_returnURL;
    }
}