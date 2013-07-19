<?php

namespace CannyDain\Shorty\Comments\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Emailing\EmailerInterface;
use CannyDain\Lib\Emailing\Models\Email;
use CannyDain\Lib\Emailing\Models\PersonDetails;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Comments\Datasource\ShortyCommentsDatasource;
use CannyDain\Shorty\Comments\Models\Comment;
use CannyDain\Shorty\Comments\Views\AddCommentForm;
use CannyDain\Shorty\Config\ShortyConfiguration;
use CannyDain\Shorty\Consumers\ConfigurationConsumer;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\EmailerConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Controllers\ShortyController;

class ShortyCommentsController extends ShortyController implements RequestConsumer, DependencyConsumer, RouterConsumer, ConfigurationConsumer, EmailerConsumer
{
    const COMMENTS_CONTROLLER_ID = __CLASS__;

    /**
     * @var ShortyConfiguration
     */
    protected $_config;

    /**
     * @var EmailerInterface
     */
    protected $_emailer;

    /**
     * @var Request
     */
    protected $_request;
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;
    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return false;
    }

    public function DeleteComment($commentID)
    {
        $returnURI = $this->_request->getParameter('returnURI');
        if ($this->_request->isPost())
            $this->datasource()->deleteComment($commentID);

        return new RedirectView($returnURI);
    }

    public function AddComment()
    {
        if (!$this->_request->isPost())
            throw new \Exception('Invalid Access');

        $view = new AddCommentForm();
        $view->setComment(new Comment());
        $view->getComment()->setPostedDateTime(time());

        $view->updateFromPost($this->_request);
        $this->datasource()->saveComment($view->getComment());

        $recipientEmail = $this->_config->getValue(ShortyConfiguration::CONFIG_KEY_EMAILING_COMMENTS_EMAIL);
        $email = new Email();
        $email->setSubject('Somebody has added a comment');
        $email->setBody('<p>Please go and check it out!</p>');
        $email->setSender(new PersonDetails('Website Comments', 'info@dannycain.com'));
        $email->addRecipient(new PersonDetails($recipientEmail, $recipientEmail));
        $this->_emailer->sendEmail($email);

        $ret = new RedirectView();
        $this->_dependencies->applyDependencies($ret);
        $ret->setResponseCode(RedirectView::RESPONSE_CODE_TEMPORARY_REDIRECT);
        $ret->setUri($view->getReturnURI());

        return $ret;
    }

    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new ShortyCommentsDatasource();
            $this->_dependencies->applyDependencies($datasource);
            $datasource->registerObjects();
        }

        return $datasource;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }

    public function consumeConfiguration(ShortyConfiguration $dependency)
    {
        $this->_config = $dependency;
    }

    public function consumeEmailer(EmailerInterface $dependency)
    {
        $this->_emailer = $dependency;
    }
}