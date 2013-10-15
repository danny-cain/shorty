<?php

namespace Sandbox\Views;

use CannyDain\Shorty\Views\ShortyView;

class FileManagerUploadView extends ShortyView
{
    protected $_callbackFunction = '';
    protected $_callbackID = 0;
    protected $_status = '';

    public function display()
    {
        echo <<<HTML
<script type="text/javascript">
    window.parent.{$this->_callbackFunction}({$this->_callbackID}, "{$this->_status}");
</script>
HTML;

    }

    public function setCallbackFunction($callbackFunction)
    {
        $this->_callbackFunction = $callbackFunction;
    }

    public function getCallbackFunction()
    {
        return $this->_callbackFunction;
    }

    public function setCallbackID($callbackID)
    {
        $this->_callbackID = $callbackID;
    }

    public function getCallbackID()
    {
        return $this->_callbackID;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function getIsAjax()
    {
        return true;
    }
}