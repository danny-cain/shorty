<?php

namespace CannyDain\ShortyModules\Comments\Models;

use CannyDain\Shorty\Models\ShortyModel;

class Comment extends ShortyModel
{
    const COMMENT_OBJECT_TYPE = __CLASS__;
    const FIELD_GUID = 'guid';
    const FIELD_POSTED_AT = 'posted';
    const FIELD_SUBJECT = 'subject';
    const FIELD_COMMENT = 'comment';
    const FIELD_AUTHOR = 'author';
    const FIELD_READ = 'read';

    protected $_id = 0;
    protected $_guid = '';
    protected $_postedAt = 0;
    protected $_subject = '';
    protected $_comment = '';
    protected $_author = '';
    protected $_read = 0;

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        $errors = array();

        if ($this->_subject == '')
            $errors[self::FIELD_SUBJECT] = 'Subject cannot be blank';

        if ($this->_comment == '')
            $errors[self::FIELD_SUBJECT] = 'Comment cannot be blank';

        return $errors;
    }

    public function save()
    {
        if ($this->_postedAt == 0)
            $this->_postedAt = time();

        parent::save();
    }


    public function setAuthor($author)
    {
        $this->_author = $author;
    }

    public function getAuthor()
    {
        return $this->_author;
    }

    public function setComment($comment)
    {
        $this->_comment = $comment;
    }

    public function getComment()
    {
        return $this->_comment;
    }

    public function setGuid($guid)
    {
        $this->_guid = $guid;
    }

    public function getGuid()
    {
        return $this->_guid;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setPostedAt($postedAt)
    {
        $this->_postedAt = $postedAt;
    }

    public function getPostedAt()
    {
        return $this->_postedAt;
    }

    public function setRead($read)
    {
        $this->_read = $read;
    }

    public function getRead()
    {
        return $this->_read;
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