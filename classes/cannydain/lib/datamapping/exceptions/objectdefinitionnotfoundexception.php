<?php

namespace CannyDain\Lib\DataMapping\Exceptions;

class ObjectDefinitionNotFoundException extends DataMapperException
{
    protected $_objectName = '';

    function __construct($objectName = null)
    {
        $this->_objectName = $objectName;
        parent::__construct();
    }

    protected function _displayMessage()
    {
        if ($this->_objectName == null)
        {
            echo '<p>Unable to find object &lt;UNKNOWN&gt;</p>';
        }
        else
            echo '<p>Unable to find object "'.$this->_objectName.'"</p>';
    }
}