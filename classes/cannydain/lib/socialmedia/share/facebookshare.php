<?php

namespace CannyDain\Lib\SocialMedia\Share;

class FacebookShare extends ShareButton
{
    const VERB_LIKE = 'like';
    const VERB_RECOMMEND = 'recommend';

    protected $_includeSendButton = false;
    protected $_showFaces = false;
    protected $_verb = self::VERB_LIKE;
    protected $_appID = '';

    public function getHTML()
    {
        $url = $this->_urlToShare;
        if($url == '')
            $url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

        $sendAttr = 'false';
        if ($this->_includeSendButton)
            $sendAttr = 'true';

        $showFaces = 'false';
        if ($this->_showFaces)
            $showFaces = 'true';

        $id = 'scriptHook_'.time().rand(100, 999);
        $host = $_SERVER['SERVER_NAME'];

        $initScript = $this->_getInitScript();
        $buttonScript = $this->_getButtonCreateScript($url, $id, $this->_includeSendButton, $this->_showFaces);

        $initScript = strtr($initScript, array('/*init-hook*/' => $buttonScript."\r\n/*init-hook*/"));

        return <<<HTML
<script type="text/javascript" id="{$id}">
    $(document).ready(function()
    {
        {$initScript}
    });
</script>
HTML;

    }

    protected function _getInitScript()
    {
        $host = $_SERVER['SERVER_NAME'];

        return <<<JAVASCRIPT
    $('body').prepend('<div id="fb-root"></div>');
    // Load the SDK asynchronously
    (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/all.js";
     fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    window.fbAsyncInit = function()
    {
        FB.init(
        {
          appId      : '{$this->_appID}',                        // App ID from the app dashboard
          channelUrl : '//{$host}/channel.html', // Channel file for x-domain c
          status     : true,                                 // Check Facebook Login status
          xfbml      : true                                  // Look for social plugins on the page
        });

        /*init-hook*/
    };
JAVASCRIPT;

    }

    protected function _getButtonCreateScript($url, $targetSelector, $sendButton = false, $showFaces = false)
    {
        $sendAttr = 'false';
        if ($sendButton)
            $sendAttr = 'true';

        $showFacesAttr = 'false';
        if ($showFaces)
            $showFacesAttr = 'true';

        return <<<JAVASCRIPT
var dynLike = document.createElement('fb:like');

dynLike.setAttribute('href', "{$url}");
dynLike.setAttribute('send', '{$sendAttr}');
dynLike.setAttribute('width', '450');
dynLike.setAttribute('show_faces', '{$showFacesAttr}');

$('#{$targetSelector}').after(dynLike); // Or wherever you want it
FB.XFBML.parse();
JAVASCRIPT;

    }

    public function setAppID($appID)
    {
        $this->_appID = $appID;
    }

    public function getAppID()
    {
        return $this->_appID;
    }

    public function setIncludeSendButton($includeSendButton)
    {
        $this->_includeSendButton = $includeSendButton;
    }

    public function getIncludeSendButton()
    {
        return $this->_includeSendButton;
    }

    public function setShowFaces($showFaces)
    {
        $this->_showFaces = $showFaces;
    }

    public function getShowFaces()
    {
        return $this->_showFaces;
    }

    public function setVerb($verb)
    {
        $this->_verb = $verb;
    }

    public function getVerb()
    {
        return $this->_verb;
    }
}