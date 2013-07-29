<?php

namespace CannyDain\Lib\SocialMedia;

use CannyDain\Lib\SocialMedia\Share\ShareButton;

class SocialMedia
{
    /**
     * @var ShareButton[]
     */
    protected $_shareButtons = array();

    public function addShareButton(ShareButton $shareButton)
    {
        $this->_shareButtons[] = $shareButton;
    }

    public function getShareButtons()
    {
        return $this->_shareButtons;
    }
}