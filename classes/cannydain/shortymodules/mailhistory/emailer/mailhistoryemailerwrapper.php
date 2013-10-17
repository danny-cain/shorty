<?php

namespace CannyDain\ShortyModules\MailHistory\Emailer;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Emailing\EmailerInterface;
use CannyDain\Lib\Emailing\Models\Email;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\ShortyModules\MailHistory\Models\MailEntry;

class MailHistoryEmailerWrapper implements EmailerInterface,DependencyConsumer
{
    /**
     * @var EmailerInterface
     */
    protected $_emailer;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @param EmailerInterface $_emailer
     */
    public function __construct($_emailer = null)
    {
        $this->_emailer = $_emailer;
    }


    public function sendEmail(Email $email)
    {
        $to = array();
        $bcc = array();
        $cc = array();
        $attachments = array();

        foreach ($email->getRecipients() as $recipient)
            $to[] = $recipient->getName()." <".$recipient->getEmail().">";

        foreach ($email->getBlindCarbonCopyRecipients() as $recipient)
            $bcc[] = $recipient->getName()." <".$recipient->getEmail().">";

        foreach ($email->getCarbonCopyRecipients() as $recipient)
            $cc[] = $recipient->getName()." <".$recipient->getEmail().">";

        foreach ($email->getAttachments() as $attachment)
            $attachments[] = $attachment->getSourceFile();

        $model = new MailEntry();
        $this->_dependencies->applyDependencies($model);

        $model->setSender($email->getSender()->getName()." <".$email->getSender()->getEmail().">");
        $model->setSubject($email->getSubject());
        $model->setHtml($email->getBody());
        $model->setPlainText($email->getPlainTextBody());

        $model->setRecipients(implode("\r\n", $to));
        $model->setAttachments($attachments);
        $model->setBcc(implode("\r\n", $bcc));
        $model->setCc(implode("\r\n", $bcc));
        $model->setSent(time());

        $model->save();

        if ($this->_emailer != null)
            $this->_emailer->sendEmail($email);
    }

    public function consumeDependencies(DependencyInjector $dependencies)
    {
        $this->_dependencies = $dependencies;
    }
}