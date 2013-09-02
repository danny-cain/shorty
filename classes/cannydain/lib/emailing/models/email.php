<?php

namespace CannyDain\Lib\Emailing\Models;

class Email
{
    /**
     * @var PersonDetails
     */
    protected $_sender;

    /**
     * @var PersonDetails[]
     */
    protected $_recipients = array();

    /**
     * @var PersonDetails[]
     */
    protected $_carbonCopyRecipients = array();

    /**
     * @var PersonDetails[]
     */
    protected $_blindCarbonCopyRecipients = array();

    protected $_subject = '';
    protected $_body = '';

    /**
     * @var Attachment[]
     */
    protected $_attachments = array();

    public function setBody($body)
    {
        $this->_body = $body;
    }

    public function getBody()
    {
        return $this->_body;
    }

    public function addRecipient(PersonDetails $recip) { $this->_recipients[] = $recip; }
    public function addCC(PersonDetails $cc) { $this->_carbonCopyRecipients[] = $cc; }
    public function addBCC(PersonDetails $bcc) { $this->_blindCarbonCopyRecipients[] = $bcc; }

    public function setAttachments($attachments)
    {
        $this->_attachments = $attachments;
    }

    public function getAttachments()
    {
        return $this->_attachments;
    }

    public function setBlindCarbonCopyRecipients($blindCarbonCopyRecipients)
    {
        $this->_blindCarbonCopyRecipients = $blindCarbonCopyRecipients;
    }

    public function getBlindCarbonCopyRecipients()
    {
        return $this->_blindCarbonCopyRecipients;
    }

    public function setCarbonCopyRecipients($carbonCopyRecipients)
    {
        $this->_carbonCopyRecipients = $carbonCopyRecipients;
    }

    public function getCarbonCopyRecipients()
    {
        return $this->_carbonCopyRecipients;
    }

    public function setRecipients($recipients)
    {
        $this->_recipients = $recipients;
    }

    public function getRecipients()
    {
        return $this->_recipients;
    }

    /**
     * @param \CannyDain\Lib\Emailing\Models\PersonDetails $sender
     */
    public function setSender($sender)
    {
        $this->_sender = $sender;
    }

    /**
     * @return \CannyDain\Lib\Emailing\Models\PersonDetails
     */
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
}