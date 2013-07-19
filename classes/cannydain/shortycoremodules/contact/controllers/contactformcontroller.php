<?php

namespace CannyDain\ShortyCoreModules\Contact\Controllers;

use CannyDain\Lib\Emailing\EmailerInterface;
use CannyDain\Lib\Emailing\Models\Email;
use CannyDain\Lib\Emailing\Models\PersonDetails;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Shorty\Config\ShortyConfiguration;
use CannyDain\Shorty\Consumers\ConfigurationConsumer;
use CannyDain\Shorty\Consumers\EmailerConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\ShortyCoreModules\Contact\DataAccess\ContactDataAccess;
use CannyDain\ShortyCoreModules\Contact\Models\UserContact;
use CannyDain\ShortyCoreModules\Contact\Views\ContactFormView;
use CannyDain\ShortyCoreModules\Contact\Views\ThankYouView;

class ContactFormController extends ShortyController implements EmailerConsumer, ConfigurationConsumer
{
    /**
     * @var ShortyConfiguration
     */
    protected $_config;

    /**
     * @var EmailerInterface
     */
    protected $_emailer;

    public function Index()
    {
        $view = new ContactFormView();
        $this->_dependencies->applyDependencies($view);

        $view->setSubmitRoute(new Route(__CLASS__));
        $view->setMessageDetails(new UserContact());
        $view->getMessageDetails()->setDateSubmitted(time());

        if ($this->_request->isPost())
        {
            $view->updateFromRequest($this->_request);
            $this->datasource()->saveUserContact($view->getMessageDetails());

            $recipientEmail = $this->_config->getValue(ShortyConfiguration::CONFIG_KEY_EMAILING_CONTACT_FORM_EMAIL);

            $email = new Email();
            $email->setSender(new PersonDetails('Website Contact Form', 'info@dannycain.com'));
            $email->setSubject('Someone filled in the contact form');
            $email->addRecipient(new PersonDetails($recipientEmail, $recipientEmail));
            $email->setBody('<p>Somebody has filled in the contact form, please go and check it!</p>');
            $this->_emailer->sendEmail($email);


            return new RedirectView($this->_router->getURI(new Route(__CLASS__, 'ThankYou')));
        }
        return $view;
    }

    public function ThankYou()
    {
        return new ThankYouView();
    }

    protected function datasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new ContactDataAccess();
            $this->_dependencies->applyDependencies($datasource);
            $datasource->registerObjects();
        }

        return $datasource;
    }

    public function consumeEmailer(EmailerInterface $dependency)
    {
        $this->_emailer = $dependency;
    }

    public function consumeConfiguration(ShortyConfiguration $dependency)
    {
        $this->_config = $dependency;
    }
}