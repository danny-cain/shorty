<?php

namespace CannyDain\ShortyCoreModules\Contact\Views;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\Contact\Models\UserContact;

class ContactFormView extends HTMLView implements RouterConsumer, FormHelperConsumer
{
    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var Route
     */
    protected $_submitRoute;

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    /**
     * @var UserContact
     */
    protected $_messageDetails;

    public function display()
    {
        echo '<h1>Contact Us</h1>';

        $this->_formHelper->startForm($this->_router->getURI($this->_submitRoute));
            $this->_formHelper->editText('name', 'Your Name', $this->_messageDetails->getFrom(), 'Your name, just so I know who I am talking to.');
            $this->_formHelper->editText('email', 'Your Email', $this->_messageDetails->getEmail(), 'If you want a reply, I\'ll need this to know where to send it.');
            $this->_formHelper->editText('subject', 'Subject', $this->_messageDetails->getSubject(), 'What your message is about.');
            $this->_formHelper->editLargeText('message', 'Message', $this->_messageDetails->getMessage());
            $this->_formHelper->submitButton('Send');
        $this->_formHelper->endForm();
    }

    public function updateFromRequest(Request $request)
    {
        $this->_messageDetails->setFrom($request->getParameter('name'));
        $this->_messageDetails->setEmail($request->getParameter('email'));
        $this->_messageDetails->setSubject($request->getParameter('subject'));
        $this->_messageDetails->setMessage($request->getParameter('message'));
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $submitRoute
     */
    public function setSubmitRoute($submitRoute)
    {
        $this->_submitRoute = $submitRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getSubmitRoute()
    {
        return $this->_submitRoute;
    }

    /**
     * @param \CannyDain\ShortyCoreModules\Contact\Models\UserContact $messageDetails
     */
    public function setMessageDetails($messageDetails)
    {
        $this->_messageDetails = $messageDetails;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\Contact\Models\UserContact
     */
    public function getMessageDetails()
    {
        return $this->_messageDetails;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }
}