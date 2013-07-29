<?php

namespace CannyDain\Lib\SocialMedia\Share;

use CannyDain\Lib\SocialMedia\SocialMediaButton;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\RequestConsumer;

abstract class ShareButton extends SocialMediaButton implements RequestConsumer
{
    /**
     * @var Request
     */
    protected $_request;

    protected $_urlToShare = '';

    public function setUrlToShare($urlToShare)
    {
        $this->_urlToShare = $urlToShare;
    }

    public function getUrlToShare()
    {
        return $this->_urlToShare;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }
}