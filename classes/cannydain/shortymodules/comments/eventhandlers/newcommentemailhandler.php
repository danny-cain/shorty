<?php

namespace CannyDain\ShortyModules\Comments\EventHandlers;

use CannyDain\Lib\Emailing\EmailerInterface;
use CannyDain\Lib\Emailing\Models\Email;
use CannyDain\Lib\Emailing\Models\PersonDetails;
use CannyDain\Shorty\Consumers\EmailConsumer;
use CannyDain\ShortyModules\Comments\Events\CommentPostedEvent;
use CannyDain\ShortyModules\Comments\Models\Comment;

class NewCommentEmailHandler implements CommentPostedEvent, EmailConsumer
{
    /**
     * @var EmailerInterface
     */
    protected $_emailer;
    protected $_fromName = '';
    protected $_fromAddress = '';
    protected $_recipientName = '';
    protected $_recipientAddress = '';
    protected $_subject = '';
    protected $_body = '';

    public function __construct($senderName = '', $senderEmail = '', $recipientName = '', $recipientEmail = '', $subject = '', $body = '')
    {
        $this->_fromName = $senderName;
        $this->_fromAddress = $senderEmail;
        $this->_recipientName = $recipientName;
        $this->_recipientAddress = $recipientEmail;
        $this->_subject = $subject;
        $this->_body = $body;
    }


    public function _event_commentPosted(Comment $comment)
    {
        $email = new Email();

        $email->setSender(new PersonDetails($this->_fromName, $this->_fromAddress));
        $email->setSubject($this->_replaceVars($this->_subject, $comment));
        $email->setBody($this->_replaceVars($this->_body, $comment));
        $email->addRecipient(new PersonDetails($this->_recipientName, $this->_recipientAddress));

        $this->_emailer->sendEmail($email);
    }

    protected function _replaceVars($subject, Comment $comment)
    {
        $subject = strtr($subject, array
        (
            '#subject#' => $comment->getSubject(),
            '#comment#' => $comment->getComment(),
            '#author#' => $comment->getAuthor()
        ));

        return $subject;
    }

    public function setBody($body)
    {
        $this->_body = $body;
    }

    public function getBody()
    {
        return $this->_body;
    }

    public function setFromAddress($fromAddress)
    {
        $this->_fromAddress = $fromAddress;
    }

    public function getFromAddress()
    {
        return $this->_fromAddress;
    }

    public function setFromName($fromName)
    {
        $this->_fromName = $fromName;
    }

    public function getFromName()
    {
        return $this->_fromName;
    }

    public function setRecipientAddress($recipientAddress)
    {
        $this->_recipientAddress = $recipientAddress;
    }

    public function getRecipientAddress()
    {
        return $this->_recipientAddress;
    }

    public function setRecipientName($recipientName)
    {
        $this->_recipientName = $recipientName;
    }

    public function getRecipientName()
    {
        return $this->_recipientName;
    }

    public function setSubject($subject)
    {
        $this->_subject = $subject;
    }

    public function getSubject()
    {
        return $this->_subject;
    }

    public function consumeEmailer(EmailerInterface $emailer)
    {
        $this->_emailer = $emailer;
    }
}