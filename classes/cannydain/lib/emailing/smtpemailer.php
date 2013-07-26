<?php

namespace CannyDain\Lib\Emailing;

use CannyDain\Lib\Emailing\Models\Email;

require dirname(__FILE__).'/phpmailer/class.phpmailer.php';
require dirname(__FILE__).'/phpmailer/class.pop3.php';
require dirname(__FILE__).'/phpmailer/class.smtp.php';

class SMTPEmailer implements EmailerInterface
{
    protected $_host = 'mail.yourhost.com';
    protected $_port = 25;
    protected $_user = '';
    protected $_pass = '';

    public function __construct($_host, $_user, $_pass, $_port = 25)
    {
        $this->_host = $_host;
        $this->_pass = $_pass;
        $this->_port = $_port;
        $this->_user = $_user;
    }


    public function sendEmail(Email $email)
    {
        $mailer = new \PHPMailer(true);

        $mailer->IsSMTP();
        $mailer->Host = $this->_host;
        $mailer->Port = $this->_port;
        $mailer->Username = $this->_user;
        $mailer->Password = $this->_pass;
        $mailer->SMTPAuth = true;
        $mailer->SMTPSecure = 'tls';

        $mailer->SetFrom($email->getSender()->getEmail(), $email->getSender()->getName());
        foreach ($email->getRecipients() as $recipient)
            $mailer->AddAddress($recipient->getEmail(), $recipient->getName());

        foreach ($email->getCarbonCopyRecipients() as $cc)
            $mailer->AddCC($cc->getEmail(), $cc->getName());

        foreach ($email->getBlindCarbonCopyRecipients() as $bcc)
            $mailer->AddBCC($bcc->getEmail(), $bcc->getName());

        $mailer->MsgHTML($email->getBody());
        foreach ($email->getAttachments() as $attachment)
            $mailer->AddAttachment($attachment);

        $mailer->Send();
    }
}