<?php

use CannyDain\Lib\Emailing\EmailerInterface;

$_SERVER['SERVER_NAME'] = 'danny.shorty.goblin';
$_SERVER['REQUEST_METHOD'] = 'GET';

require dirname(dirname(__FILE__)).'/public/initialise.php';

class MailerMain implements \CannyDain\Shorty\Consumers\EmailConsumer
{
    /**
     * @var EmailerInterface
     */
    protected $_emailer;

    public static function main()
    {
        $main = new MailerMain();
        ShortyInit::Initialise(array($main));
        $main->execute();
    }

    public function execute()
    {
        $email = new \CannyDain\Lib\Emailing\Models\Email();

        $email->addRecipient(new \CannyDain\Lib\Emailing\Models\PersonDetails('Danny', 'danny@dannycain.com'));
        $email->setSubject('Hi There');
        $email->setBody('<h1>Test Email</h1><p>This is a test email from the command line emailer script</p>.');
        $email->setSender(new \CannyDain\Lib\Emailing\Models\PersonDetails('Info', 'info@dannycain.com'));

        $attachment = new \CannyDain\Lib\Emailing\Models\Attachment('error_log', 'plain/text', dirname(dirname(__FILE__)).'/public/error_log');
        $email->addAttachment($attachment);

        $this->_emailer->sendEmail($email);
        echo "Sent!";
    }

    public function consumeEmailer(EmailerInterface $emailer)
    {
        $this->_emailer = $emailer;
    }
}

MailerMain::main();