<?php

namespace CannyDain\ShortyModules\MailHistory\Models;

use CannyDain\Shorty\Models\ShortyModel;

class MailEntry extends ShortyModel
{
    const OBJECT_TYPE_MAIL_ENTRY = __CLASS__;

    protected $_id = 0;
    protected $_sender = '';
    protected $_recipients =  '';
    protected $_cc = '';
    protected $_bcc = '';
    protected $_subject = '';
    protected $_html = '';
    protected $_plainText = '';
    protected $_attachments = array();
    protected $_sent = 0;

    public function setSent($sent)
    {
        $this->_sent = $sent;
    }

    public function getSent()
    {
        return $this->_sent;
    }

    public function setAttachments($attachments)
    {
        $this->_attachments = $attachments;
    }

    public function getAttachments()
    {
        return $this->_attachments;
    }

    public function setBcc($bcc)
    {
        $this->_bcc = $bcc;
    }

    public function getBcc()
    {
        return $this->_bcc;
    }

    public function setCc($cc)
    {
        $this->_cc = $cc;
    }

    public function getCc()
    {
        return $this->_cc;
    }

    public function setHtml($html)
    {
        $this->_html = $html;
    }

    public function getHtml()
    {
        return $this->_html;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setPlainText($plainText)
    {
        $this->_plainText = $plainText;
    }

    public function getPlainText()
    {
        return $this->_plainText;
    }

    public function setRecipients($recipients)
    {
        $this->_recipients = $recipients;
    }

    public function getRecipients()
    {
        return $this->_recipients;
    }

    public function setSender($sender)
    {
        $this->_sender = $sender;
    }

    public function getSender()
    {
        return $this->_sender;
    }

    public function setSubject($subject)
    {
        $this->_subject = $subject;
    }

    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        return array();
    }
}