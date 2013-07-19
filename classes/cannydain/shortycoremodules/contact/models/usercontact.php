<?php

namespace CannyDain\ShortyCoreModules\Contact\Models;

class UserContact
{
    protected $_id = 0;
    protected $_from = '';
    protected $_email = '';
    protected $_dateSubmitted = 0;
    protected $_subject = '';
    protected $_message = '';

    public function setDateSubmitted($dateSubmitted)
    {
        $this->_dateSubmitted = $dateSubmitted;
    }

    public function getDateSubmitted()
    {
        return $this->_dateSubmitted;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setFrom($from)
    {
        $this->_from = $from;
    }

    public function getFrom()
    {
        return $this->_from;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setMessage($message)
    {
        $this->_message = $message;
    }

    public function getMessage()
    {
        return $this->_message;
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