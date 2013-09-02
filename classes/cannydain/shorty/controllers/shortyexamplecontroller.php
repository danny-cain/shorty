<?php

namespace CannyDain\Shorty\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Emailing\EmailerInterface;
use CannyDain\Lib\Emailing\Models\Email;
use CannyDain\Lib\Emailing\Models\PersonDetails;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\EmailConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Views\ExampleFormView;
use CannyDain\Shorty\Views\HomepageView;
use phpmailerException;

class ShortyExampleController extends ShortyController implements EmailConsumer
{
    /**
     * @var EmailerInterface
     */
    protected $_emailer;

    public function Index()
    {
        return new HomepageView;
    }

    public function Form()
    {
        session_start();
        $name = '';
        $age = 0;

        if (isset($_SESSION['name']))
            $name = $_SESSION['name'];
        if (isset($_SESSION['age']))
            $age = $_SESSION['age'];

        $view = new ExampleFormView();
        $view->setRoute(new Route(__CLASS__, 'Form'));
        $view->setName($name);
        $view->setAge($age);

        $this->_dependencies->applyDependencies($view);

        if ($view->updateFromPostAndReturnTrueIfPostedAndValid())
        {
            $_SESSION['name'] = $view->getName();
            $_SESSION['age'] = $view->getAge();

            $uri = $this->_router->getURI(new Route(__CLASS__, 'Form'));
            return new RedirectView($uri);
        }
        else
            echo '<pre>'.print_r($_SESSION, true).'</pre>';

        return $view;
    }

    public function EmailDanny()
    {
        $email = new Email();
        $email->setSender(new PersonDetails('DC dot Com', 'no-reply@dannycain.com'));
        $email->setSubject('Homepage Visited');
        $email->setBody('<p>The homepage was visited</p>');
        $email->addRecipient(new PersonDetails('Danny Cain', 'danny@dannycain.com'));

        try
        {
            $this->_emailer->sendEmail($email);
        }
        catch(PHPMailerException $e)
        {
            echo $e->errorMessage();
        }

        return new HomepageView();
    }

    public function consumeEmailer(EmailerInterface $emailer)
    {
        $this->_emailer = $emailer;
    }
}