<?php

namespace CannyDain\Shorty\Comments\Models;

class Comment
{
    protected $_id = 0;
    protected $_objectGUID = '';
    protected $_authorName = '';
    protected $_authorEmail = '';
    protected $_subject = '';
    protected $_comment = '';
    protected $_postedDateTime = 0;

    public function setObjectGUID($objectGUID)
    {
        $this->_objectGUID = $objectGUID;
    }

    public function getObjectGUID()
    {
        return $this->_objectGUID;
    }

    public function setAuthorEmail($authorEmail)
    {
        $this->_authorEmail = $authorEmail;
    }

    public function getAuthorEmail()
    {
        return $this->_authorEmail;
    }

    public function setAuthorName($authorName)
    {
        $this->_authorName = $authorName;
    }

    public function getAuthorName()
    {
        return $this->_authorName;
    }

    public function setComment($comment)
    {
        $this->_comment = $comment;
    }

    public function getComment()
    {
        return $this->_comment;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setPostedDateTime($postedDateTime)
    {
        $this->_postedDateTime = $postedDateTime;
    }

    public function getPostedDateTime()
    {
        return $this->_postedDateTime;
    }

    public function setSubject($subject)
    {
        $this->_subject = $subject;
    }

    public function getSubject()
    {
        return $this->_subject;
    }
}