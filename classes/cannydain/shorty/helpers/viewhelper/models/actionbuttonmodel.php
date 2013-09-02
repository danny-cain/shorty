<?php

namespace CannyDain\Shorty\Helpers\ViewHelper\Models;

class ActionButtonModel
{
    const ACTION_POST = 'POST';
    const ACTION_GET = 'GET';

    protected $_action = self::ACTION_GET;
    protected $_uri = '';
    protected $_caption = '';
    protected $_confirmationMessage = '';
    protected $_extraFields = array();

    function __construct($_caption = '', $_uri = '', $_action = self::ACTION_GET, $_confirmationMessage = '', $_extraFields = array())
    {
        $this->_action = $_action;
        $this->_caption = $_caption;
        $this->_confirmationMessage = $_confirmationMessage;
        $this->_extraFields = $_extraFields;
        $this->_uri = $_uri;
    }

    public function setAction($action)
    {
        $this->_action = $action;
    }

    public function getAction()
    {
        return $this->_action;
    }

    public function setCaption($caption)
    {
        $this->_caption = $caption;
    }

    public function getCaption()
    {
        return $this->_caption;
    }

    public function setConfirmationMessage($confirmationMessage)
    {
        $this->_confirmationMessage = $confirmationMessage;
    }

    public function getConfirmationMessage()
    {
        return $this->_confirmationMessage;
    }

    public function setExtraFields($extraFields)
    {
        $this->_extraFields = $extraFields;
    }

    public function getExtraFields()
    {
        return $this->_extraFields;
    }

    public function setUri($uri)
    {
        $this->_uri = $uri;
    }

    public function getUri()
    {
        return $this->_uri;
    }
}